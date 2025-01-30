(function(l){var s=0;l.fn.fileUpload=function(){return this.each(function(){var a=l(this),t=`fileUpload-${++s}`,n=`
                <label for="${t}" class="file-upload image-upload__box">
                    <div class="image-upload__boxInner">
                        <i class="ri-gallery-line image-upload__icon"></i>
                        <p class="text-xs text-secondary-light mt-4 mb-0">Drag & drop image here</p>
                    </div>
                    <input type="file" id="${t}" name="[]" multiple hidden />
                </label>
            `;a.html(n).addClass("file-container");function o(e){if(e.length>0){var i=e[0],p=i.name;(i.size/1024).toFixed(2)+"";var f=i.type,u=f.startsWith("image")?`<img src="${URL.createObjectURL(i)}" alt="${p}" class="image-upload__image" height="30">`:' <span class="image-upload__anotherFileIcon"> <i class="fas fa-file"></i></span>',r=a.find("label.file-upload");r.find(".image-upload__boxInner").html(`
                        ${u}
                        <button type="button" class="image-upload__deleteBtn"><i class="ri-close-line"></i></button>
                    `),r.find(".image-upload__deleteBtn").click(function(){a.html(n),d()})}}function d(){a.on({dragover:function(e){e.preventDefault(),a.toggleClass("dragover",e.type==="dragover")},drop:function(e){e.preventDefault(),a.removeClass("dragover"),o(e.originalEvent.dataTransfer.files)}}),a.find('label.file-upload input[type="file"]').change(function(){o(this.files)})}d()})}})(jQuery);$(".fileUpload").fileUpload();
