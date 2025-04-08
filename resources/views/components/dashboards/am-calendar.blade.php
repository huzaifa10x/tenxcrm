<button class="btn btn-neutral-900 my-10 d-block " type="button" data-bs-toggle="offcanvas" data-bs-target="#todoOffcanvas"
    aria-controls="todoOffcanvas">Todos</button>
<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="todoOffcanvas"
    aria-labelledby="todoOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="todoOffcanvasLabel">Todos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
        <div id="calendar"></div>
        <input type="hidden" id="todoDate" class="form-control mb-3">
        <div id="todoForm" class="mt-10">
            <div class="input-group">
                <input type="text" id="todoTask" class="form-control" placeholder="Enter Todo">
                <button class="input-group-text bg-base" id="saveTodo"> <iconify-icon
                        icon="lucide:plus"></iconify-icon>Add</button>
            </div>
        </div>
        <ul id="todoList" class="list-group"></ul>
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
                                `<button class="p-1 fs-8 btn btn-sm btn-success mark-done" data-id="${todo.id}">✔</button>` :
                                '';

                            let listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'd-flex',
                                'justify-content-between', 'align-items-center', 'mt-10');
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
