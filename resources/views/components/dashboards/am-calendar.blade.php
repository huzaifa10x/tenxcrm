<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<span class="fs-4 fw-semibold mt-24">Todos</span>
<div class="col-lg-6">
    <div class="card h-100 p-3 radius-8 border-0">
        <div id="calendar"></div>
    </div>
</div>
<div class="col-lg-6">
    <div class="card h-100 radius-8 border-0">
        <div class="card-header">
            <input type="hidden" id="todoDate" class="form-control mb-3">
            <div id="todoForm" class="d-none">
                <div class="input-group">
                    <input type="text" id="todoTask" class="form-control" placeholder="Enter Todo">
                    <button class="input-group-text bg-base" id="saveTodo"> <iconify-icon
                            icon="lucide:plus"></iconify-icon>Add</button>
                </div>
            </div>
        </div>
        <div class="card-body" style="max-height: 500px; overflow-y: auto">
            <ul id="todoList" class="list-group mt-2"></ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById('todoDate').value = today;
        fetchTodos(today);

        // Fetch and display todos for selected date
        function fetchTodos(selectedDate) {
            document.getElementById('todoForm').classList.remove('d-none');
            fetch(`{{ route('todo.getByDate') }}?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    let todoList = document.getElementById('todoList');
                    todoList.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(todo => {
                            let statusIcon = todo.status === 'completed' ? '✅' : '⏳';
                            let actionButton = todo.status === 'pending' ?
                                `<button class="btn btn-sm btn-success mark-done" data-id="${todo.id}">✔</button>` :
                                '';

                            let listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'd-flex',
                                'justify-content-between', 'align-items-center');
                            listItem.innerHTML =
                                `<span>${statusIcon} ${todo.task}</span> ${actionButton}`;
                            todoList.appendChild(listItem);
                        });
                    } else {
                        todoList.innerHTML =
                            `<li class="list-group-item text-muted">No tasks found for this date.</li>`;
                    }
                });
        }

        // Handle date change
        document.getElementById('todoDate').addEventListener('change', function() {
            fetchTodos(this.value);
        });

        // Save new todo
        document.getElementById('saveTodo').addEventListener('click', function() {
            let date = document.getElementById('todoDate').value;
            let task = document.getElementById('todoTask').value.trim();

            if (!task) {
                alert("Please enter a task!");
                return;
            }

            fetch("{{ route('todo.store') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    date: date,
                    task: task
                })
            }).then(response => response.json()).then(() => {
                document.getElementById('todoTask').value = '';
                fetchTodos(date);
                calendar.refetchEvents();
            });
        });

        // Mark task as done (Event Delegation)
        document.getElementById('todoList').addEventListener('click', function(e) {
            if (e.target.classList.contains('mark-done')) {
                let todoId = e.target.dataset.id;
                fetch("{{ route('todo.markDone') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: todoId
                    })
                }).then(response => response.json()).then(() => {
                    fetchTodos(document.getElementById('todoDate').value); // Refresh list
                });
            }
        });

        // Initialize FullCalendar
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            dateClick: function(info) {
                document.getElementById('todoDate').value = info.dateStr;
                fetchTodos(info.dateStr);
            },
            events: function(fetchInfo, successCallback) {
                fetch("{{ route('todo.getDates') }}")
                    .then(response => response.json())
                    .then(dates => {
                        let events = dates.map(date => ({
                            title: date?.task,
                            start: date?.todo_date,
                            allDay: true,
                            color: "#ffcc00"
                        }));
                        successCallback(events);
                    });
            }
        });

        calendar.render();
    });
</script>
