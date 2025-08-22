{{-- resources/views/components/sortable-car-images.blade.php --}}

<div class="sortable-list-wrapper">
    <form id="syncImagesForm"
          method="POST"
          action="{{ route('car.syncImages', $car) }}"
          enctype="multipart/form-data">
        @csrf

        {{-- File input / Add new images --}}
        <label class="add-images">
            <input
                type="file"
                id="fileInput"
                name="images[]"
                multiple accept=".jpg,.jpeg,.png"
                hidden
            >
            <i class="fa-solid fa-plus"></i>
            <strong>Add Images</strong>
            <p style="font-size:12px;color:#64748b;">Click to select images from your device</p>
        </label>

        <p class="subtitle">Drag to reorder • Click trash to mark for deletion</p>

        {{-- Container for sortable items --}}
        <div id="list"></div>

        {{-- Hidden input for JSON payload (delete_images + positions) --}}
        <input type="hidden" name="payload" id="payloadInput">

        <div class="submit-section">
            <div>
                <h3>Ready to Submit</h3>
                <p id="markedCount">No issues</p>
            </div>
            <button type="button" id="submitBtn">
                <i class="fa-solid fa-paper-plane"></i> Submit
            </button>
        </div>
    </form>

    <script>
    window.carImages = @json($images);

    document.addEventListener('DOMContentLoaded', () => {
        const MAX_VALID = 12;
        const MAX_SIZE = 2 * 1024 * 1024; // 2MB
        const ordinals = ["Primary Image","Second Image","Third Image","Fourth Image","Fifth Image","Sixth Image","Seventh Image","Eighth Image","Ninth Image","Tenth Image","Eleventh Image","Twelfth Image"];

        // Build frontend image state
        const items = (window.carImages || []).map(img => ({
            id: parseInt(img.id, 10),
            image: img.image,
            uiState: 'valid',
            car_id: img.car_id,
            original_filename: img.original_filename,
            status: img.status
        }));

        let draggedIndex = null;
        let tempIdCounter = 100000;

        const list = document.getElementById('list');
        const fileInput = document.getElementById('fileInput');
        const submitBtn = document.getElementById('submitBtn');
        const payloadInput = document.getElementById('payloadInput');
        const form = document.getElementById('syncImagesForm');

        function renderList() {
            list.innerHTML = '';
            const validItems = items.filter(i => i.uiState === 'valid');
            validItems.forEach((i, idx) => { if(idx >= MAX_VALID) i.uiState = 'tooMany'; });

            items.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'list-item';
                if (item.uiState === 'marked') div.classList.add('marked');
                if (item.uiState === 'tooMany') div.classList.add('too-many');
                if (item.uiState === 'tooBig') div.classList.add('too-big');
                div.draggable = true;

                // Drag events
                div.addEventListener('dragstart', () => { draggedIndex = index; div.classList.add('dragging'); });
                div.addEventListener('dragend', () => { draggedIndex = null; div.classList.remove('dragging'); });
                div.addEventListener('dragover', e => { e.preventDefault(); div.classList.add('over'); });
                div.addEventListener('dragleave', () => div.classList.remove('over'));
                div.addEventListener('drop', () => {
                    const draggedItem = items.splice(draggedIndex, 1)[0];
                    items.splice(index, 0, draggedItem);
                    renderList();
                });

                let posNumHTML = '';
                if(item.uiState === 'valid') posNumHTML = validItems.indexOf(item) + 1;
                else if(item.uiState === 'marked') posNumHTML = `<i class="fa-solid fa-trash trash-icon"></i>`;
                else if(item.uiState === 'tooMany') posNumHTML = `<i class="fa-solid fa-ban ban-icon-amber"></i>`;
                else if(item.uiState === 'tooBig') posNumHTML = `<i class="fa-solid fa-ban ban-icon-red"></i>`;

                let title = '', desc = '';
                if(item.uiState === 'valid') {
                    const pos = validItems.indexOf(item);
                    title = ordinals[pos] || `${pos+1}th Image`;
                    desc = "Ready to submit!";
                } else if(item.uiState === 'marked') { title="Delete Image"; desc="Marked for deletion"; }
                else if(item.uiState === 'tooMany') { title="Too many images"; desc="This image will not be uploaded!"; }
                else if(item.uiState === 'tooBig') { title="Image size is too big"; desc="Images may not be more than 2MB"; }

                div.innerHTML = `
                    <i class="fa-solid fa-grip-vertical grip"></i>
                    <div class="pos-num">${posNumHTML}</div>
                    <img src="${item.image}" alt="">
                    <div class="info"><h3>${title}</h3><p>${desc}</p></div>
                    <div class="trash-btn"><i class="fa-solid fa-trash"></i></div>
                `;

                div.querySelector('.trash-btn').addEventListener('click', () => {
                    item.uiState = item.uiState === 'valid' ? 'marked' : 'valid';
                    renderList();
                });

                list.appendChild(div);
            });

            updateMarkedCount();
        }

        function updateMarkedCount() {
            const markedCountEl = document.getElementById('markedCount');
            const tooManyCount = items.filter(i => i.uiState==='tooMany').length;
            const tooBigCount = items.filter(i => i.uiState==='tooBig').length;
            const markedCount = items.filter(i => i.uiState==='marked').length;
            const parts = [];
            if(tooManyCount) parts.push(`There ${tooManyCount===1?'is':'are'} ${tooManyCount} item${tooManyCount>1?'s':''} too many`);
            if(tooBigCount) parts.push(`${tooBigCount} item${tooBigCount>1?'s':''} too big`);
            if(markedCount) parts.push(`${markedCount} item${markedCount>1?'s':''} marked for deletion`);
            markedCountEl.textContent = parts.join(', ') || 'No issues';
        }

        renderList();

        fileInput.addEventListener('change', e => {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                const validTypes = ['image/jpeg','image/png','image/jpg'];
                if(!validTypes.includes(file.type)) {
                    alert(`"${file.name}" is not supported. Only JPEG/PNG.`);
                    return;
                }
                const uiState = file.size > MAX_SIZE ? 'tooBig' : 'valid';
                const reader = new FileReader();
                reader.onload = () => {
                    items.push({
                        id: tempIdCounter++,       // unique frontend temp ID
                        image: reader.result,      // preview
                        uiState,
                        original_filename: file.name,
                        file                        // store actual File object
                    });
                    renderList();
                };
                reader.readAsDataURL(file);
            });
        });

        /*
        What we need to send in payload
        [
            { id: 12, action: 'keep' },                 // existing image
            { id: 15, action: 'delete' },               // existing image marked for deletion
            { id: 'temp-1', action: 'upload', file: <file> }  // new image
        ]
        - id = existing DB ID or frontend temp ID
        - action = 'keep' | 'delete' | 'upload'
        - file = only present for new uploads
        */

        // Submit handler
        submitBtn.addEventListener('click', () => {
            const payload = [];
            let order = 1;

            items.forEach(item => {
                if(item.uiState === 'tooMany' || item.uiState === 'tooBig') return;

                if(item.uiState === 'marked' && item.car_id) {
                    payload.push({id: item.id, action: 'delete'});
                }
                else if(item.uiState === 'valid' && !item.car_id) {
                    payload.push({id: item.id, action: 'upload', tempId: item.id});
                }
                else if(item.uiState === 'valid' && item.car_id) {
                    payload.push({id: item.id, action: 'keep'});
                }

                // set position if it's valid
                if(item.uiState === 'valid') {
                    payload[payload.length-1].position = order++;
                }
            });

            payloadInput.value = JSON.stringify(payload);

            // 🔑 Build FormData to attach new files in correct order
            const formData = new FormData();
            formData.append('_token', form.querySelector('input[name=_token]').value);
            formData.append('payload', payloadInput.value);

            // Append files in order
            items.filter(i => i.uiState==='valid' && !i.car_id && i.file).forEach(fileItem => {
                formData.append('images[]', fileItem.file, fileItem.original_filename);
            });

            // CHANGED: Log full formData before sending
            console.log("Submitting syncImages payload:", payload);
            for (let [key, val] of formData.entries()) {
                console.log("formData =>", key, val);
            }

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value}
            }).then(() => window.location.reload());
        });
    });
    </script>
</div>
