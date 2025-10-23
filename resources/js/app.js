// resources/js/app.js

import axios from 'axios';
import Alpine from 'alpinejs';

// Add this before document.addEventListener
window.fuelTypeSelector = function(config) {
  return {
    fuelTypes: config.fuelTypes || [],
    selectedId: config.selectedId || null,
    selectedName: config.selectedName || 'Select Fuel Type',
    isOpen: false,

    openModal() {
      this.isOpen = true;
      document.body.style.overflow = 'hidden';
    },

    closeModal() {
      this.isOpen = false;
      document.body.style.overflow = '';
    },

    selectFuelType(fuelType) {
      this.selectedId = fuelType.id;
      this.selectedName = fuelType.name;
    },

    confirmSelection() {
      this.closeModal();
    }
  };
};

document.addEventListener("DOMContentLoaded", function () {

  // ----------------------------
  // Hero Image Slider
  // ----------------------------
  const initSlider = () => {
    const slides = document.querySelectorAll(".hero-slide");
    let currentIndex = 0; // Track the current slide
    const totalSlides = slides.length;

    function moveToSlide(n) {
      slides.forEach((slide, index) => {
        slide.style.transform = `translateX(${-100 * n}%)`;
        if (n === index) {
          slide.classList.add("active");
        } else {
          slide.classList.remove("active");
        }
      });
      currentIndex = n;
    }

    // Function to go to the next slide
    function nextSlide() {
      if (currentIndex === totalSlides - 1) {
        moveToSlide(0); // Go to the first slide if we're at the last
      } else {
        moveToSlide(currentIndex + 1);
      }
    }

    // Function to go to the previous slide
    function prevSlide() {
      if (currentIndex === 0) {
        moveToSlide(totalSlides - 1); // Go to the last slide if we're at the first
      } else {
        moveToSlide(currentIndex - 1);
      }
    }

    // Example usage with buttons
    // Assuming you have buttons with classes `.next` and `.prev` for navigation
    const carouselNextButton = document.querySelector(".hero-slide-next");
    if (carouselNextButton) {
      carouselNextButton.addEventListener("click", nextSlide);
    }
    const carouselPrevButton = document.querySelector(".hero-slide-prev");
    if (carouselPrevButton) {
      carouselPrevButton.addEventListener("click", prevSlide);
    }

    // Initialize the slider
    moveToSlide(0);
  };

// ----------------------------
  // Flash Messages Alpine Setup
  // ----------------------------
  function flashMessages(initialMessages = []) {
    return {
      messages: initialMessages.map((m, i) => ({
        id: Date.now() + i,
        type: m.type,
        text: m.text,
        icon: m.type === 'success'
          ? 'fa-solid fa-flag-checkered'
          : m.type === 'info'
          ? 'fa-solid fa-circle-info'
          : m.type === 'warning'
          ? 'fa-solid fa-triangle-exclamation'
          : 'fa-solid fa-car-burst',
        cssClass: m.type === 'success'
          ? 'flash-success-message'
          : m.type === 'info'
          ? 'flash-info-message'
          : m.type === 'warning'
          ? 'flash-warning-message'
          : 'flash-error-message',
        visible: true
      })),
      dismiss(id) {
        const msg = this.messages.find(m => m.id === id);
        if (msg) msg.visible = false;
        setTimeout(() => {
          this.messages = this.messages.filter(m => m.id !== id);
        }, 300);
      },
      init() {
        this.messages.forEach(msg => {
          setTimeout(() => this.dismiss(msg.id), 4000);
        });
      }
    }
  }

  // Make flashMessages globally available for Alpine
  window.flashMessages = flashMessages;

  // ----------------------------
  // Image Upload Picker and Previews
  // - On "New Vehicle" and "Edit Vehicle" Pages
  // ----------------------------
  const initImagePicker = () => {
    const fileInput = document.querySelector("#vehicleFormImageUpload");
    const imagePreview = document.querySelector("#imagePreviews");
    if (!fileInput) {
      return;
    }
    fileInput.onchange = (ev) => {
      imagePreview.innerHTML = "";
      const files = ev.target.files;
      for (let file of files) {
        readFile(file).then((url) => {
          const img = createImage(url);
          imagePreview.append(img);
        });
      }
    };

    function readFile(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (ev) => {
          resolve(ev.target.result);
        };
        reader.onerror = (ev) => {
          reject(ev);
        };
        reader.readAsDataURL(file);
      });
    }

    function createImage(url) {
      const a = document.createElement("a");
      a.classList.add("vehicle-form-image-preview");
      a.innerHTML = `
        <img src="${url}" />
      `;
      return a;
    }
  };


  // ----------------------------
  // Mobile Navbar
  // ----------------------------
  const initMobileNavbar = () => {
    const btnToggle = document.querySelector(".btn-navbar-toggle");
    if (!btnToggle) return; // ← Guard

    btnToggle.onclick = () => {
      document.body.classList.toggle("navbar-opened");
    };
  };

  // ----------------------------
  // Image Carousel on "Show Vehicle" Page
  // ----------------------------
  const imageCarousel = () => {
    const carousel = document.querySelector('.vehicle-images-carousel');
    if (!carousel) return;

    const thumbnails = document.querySelectorAll('.vehicle-image-thumbnails img');
    const activeImage = document.getElementById('activeImage');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    if (!activeImage || !prevButton || !nextButton) return; // ← Guard


    let currentIndex = 0;

    // Initialize active thumbnail class
    thumbnails.forEach((thumbnail, index) => {
      if (thumbnail.src === activeImage.src) {
        thumbnail.classList.add('active-thumbnail');
        currentIndex = index;
      }
    });

    // Function to update the active image and thumbnail
    const updateActiveImage = (index) => {
      activeImage.src = thumbnails[index].src;
      thumbnails.forEach(thumbnail => thumbnail.classList.remove('active-thumbnail'));
      thumbnails[index].classList.add('active-thumbnail');
    };

    // Add click event listeners to thumbnails
    thumbnails.forEach((thumbnail, index) => {
      thumbnail.addEventListener('click', () => {
        currentIndex = index;
        updateActiveImage(currentIndex);
      });
    });

    // Add click event listener to the previous button
    prevButton.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
      updateActiveImage(currentIndex);
    });

    // Add click event listener to the next button
    nextButton.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % thumbnails.length;
      updateActiveImage(currentIndex);
    });
  }

  // ----------------------------
  // Toggle Search on Mobile
  // ----------------------------
  const initMobileFilters = () => {
    const filterButton = document.querySelector('.show-filters-button');
    const sidebar = document.querySelector('.search-vehicles-sidebar');
    const closeButton = document.querySelector('.close-filters-button');

    if (!filterButton || !sidebar) return; // ← Guard

    console.log(filterButton.classList)
    filterButton.addEventListener('click', () => {
      if (sidebar.classList.contains('opened')) {
        sidebar.classList.remove('opened')
      } else {
        sidebar.classList.add('opened')
      }
    });

    if (closeButton) {
      closeButton.addEventListener('click', () => {
        sidebar.classList.remove('opened')
      })
    }
  }

  // ----------------------------
  // Mileage Input Component
  // ----------------------------
  const initMileageInputs = () => {
    const mileageGroups = document.querySelectorAll('.mileage-input-group');

    // Exit early if no mileage inputs found on page
    if (mileageGroups.length === 0) {
      return;
    }

    mileageGroups.forEach(group => {
      const input = group.querySelector('.mileage-input');
      const toggle = group.querySelector('.unit-toggle');
      const label = group.querySelector('.mileage-label');
      const helperText = group.querySelector('.helper-text');
      const errorMessage = group.querySelector('.error-message');
      const hiddenInput = group.querySelector('.mileage-unit-input');

      // Check if there's an error - if so, hide helper text
      const hasError = group.classList.contains('has-error') && errorMessage.textContent.trim() !== '';

      // Unit configuration
      const units = {
        km: {
          label: 'Odometer Reading (km)',
          helper: 'Click (km) to change unit',
          next: 'mi',
          max: 99999999
        },
        mi: {
          label: 'Odometer Reading (mi)',
          helper: 'Click (mi) to change unit',
          next: 'hrs',
          max: 99999999
        },
        hrs: {
          label: 'Hour Meter Reading (hrs)',
          helper: 'Click (hrs) to change unit',
          next: 'km',
          max: 999999
        },
      };

      let currentUnit = input.dataset.unit || 'km';

      // Update UI based on current unit
      const updateUI = (unit) => {
        const config = units[unit];
        label.textContent = config.label;

        // Only update helper text if there's no error
        if (!hasError) {
          helperText.textContent = config.helper;
        }

        toggle.textContent = unit;
        toggle.dataset.unit = unit;
        input.dataset.unit = unit;
        hiddenInput.value = unit;
        currentUnit = unit;
      };

      // Handle unit toggle click
      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const nextUnit = units[currentUnit].next;
        updateUI(nextUnit);
    });

      // Input validation
      input.addEventListener('input', (e) => {
        let value = e.target.value;

        // Remove any non-numeric characters
        value = value.replace(/[^\d]/g, '');

        // Ensure positive numbers only
        if (value && parseInt(value) < 0) {
          value = '0';
        }

        // Enforce max value
        const max = units[currentUnit].max;
        if (value && parseInt(value) > max) {
          value = max.toString();
        }

        e.target.value = value;
      });

      // Prevent decimal input
      input.addEventListener('keypress', (e) => {
        if (e.key === '.' || e.key === ',') {
          e.preventDefault();
        }
      });

      // Hide helper text if there's an error
      if (hasError) {
        helperText.style.display = 'none';
      }

      // Initialize with default unit
      updateUI(currentUnit);
    });
  };


  // ----------------------------
  // Cascading Dropdown Selector
  // - For "Model" on "Manufacturer"
  // - For "City" on "Province"
  // ----------------------------
  const initCascadingDropdown = (parentSelector, childSelector) => {
    const parentDropdown = document.querySelector(parentSelector);
    const childDropdown = document.querySelector(childSelector);

    if (!parentDropdown || !childDropdown) return;

    hideModelOptions(parentDropdown.value)

    parentDropdown.addEventListener('change', (ev) => {
      hideModelOptions(ev.target.value)
      childDropdown.value = ''
    });

    function hideModelOptions(parentValue) {
      const models = childDropdown.querySelectorAll('option');
      models.forEach(model => {
        if (model.dataset.parent === parentValue || model.value === '') {
          model.style.display = 'block';
        } else {
          model.style.display = 'none';
        }
      });
    }
  }

  // ----------------------------
  // Order Lisings Dropdown
  // - By Price, Year, Milage and Date Listed
  // - Used on "Search" Page
  // ----------------------------
  const initSortingDropdown = () => {
    const sortingDropdown = document.querySelector('.sort-dropdown');
    if (!sortingDropdown) return;

    // Init sorting dropdown with the current value
    const url = new URL(window.location.href);
    const sortValue = url.searchParams.get('sort');
    if (sortValue) {
      sortingDropdown.value = sortValue;
    }

    sortingDropdown.addEventListener('change', (ev) => {
      const url = new URL(window.location.href);
      url.searchParams.set('sort', ev.target.value);
      window.location.href = url.toString();
    });
  }

  // ----------------------------
  // Add Vehicle To Watchlist
  // - Used on "Listing" and "Search" Pages
  // ----------------------------
  const initAddToWatchlist = () => {
    // Select add to watchlist buttons
    const buttons = document.querySelectorAll('.btn-heart');
    // Iterate over these buttons and add click event listener
    buttons.forEach((button) => {
        button.addEventListener('click', ev => {
            // debugger;
            // Get the button element on which click happened
            const button = ev.currentTarget;
            // We added data-url attribute to the button in blade file
            // get the url
            const url = button.dataset.url;
            // Make request on the URL to add or remove the vehicle from watchlist
            axios.post(url).then((response) => {
                // Select both svg tags of the button
                const toShow = button.querySelector('svg.hidden');
                const toHide = button.querySelector('svg:not(.hidden)');
                // Which was hidden must be displayed
                toShow.classList.remove('hidden')
                // Which was displayed must be hidden
                toHide.classList.add('hidden')
                // Show alert to the user
                // alert(response.data.message)
            })
            .catch(error => {
                // If error happened, we can log it to console
                console.error(error.response)
                // If error exists, and it has response with status 401
                if (error?.response?.status === 401) {
                    // Show alert to the user
                    alert("Please log in first to add vehicles into your watchlist.")
                } else {
                    alert("Internal Server Error. Please Try again later!")
                }
            })
        })
    })
  }

  // ----------------------------
  // Show Phone Number
  // - Used on "Show" Page
  // ----------------------------
  const initShowPhoneNumber = () => {
    // Select the element we need to listen to click
    const span = document.querySelector('.vehicle-details-phone-view');
    if (!span) return; // ← Guard

    span.addEventListener('click', ev => {
      // Prevent default action of the click
      ev.preventDefault();

      // Get the url on which we should make Ajax request
      const url = span.dataset.url;

      // Make the request
      axios.post(url).then(response => {

        // Get response from backend and take actual phone number
        const phone = response.data.phone;

        // Find the <a> element
        const a = span.parentElement;

        // and update its href attribute with full phone number received from backend
        a.href = 'tel:' + phone;

        // Find the element which contains obfuscated text and update it
        const phoneEl = a.querySelector('.text-phone')
        phoneEl.innerText = phone;
      })
    })
  }

  // ----------------------------
  // Poll Vehicle Images Helper
  // ----------------------------
  function initPollVehicleImages({ onUpdate, interval = 5000, maxTries = 12 }) {
      let tries = 0;
      let intervalId = null;

      const fetchStatus = () => {
          tries++;
          axios.get('/api/vehicle-image/status')
              .then(response => {
                  onUpdate(response.data);

                  const allLoaded = response.data.every(vehicle => {
                      const images = vehicle.images.concat(vehicle.primary_image ? [vehicle.primary_image] : []);
                      return images.every(img => img.status === 'completed' || img.status === 'failed');
                  });

                  if (allLoaded || tries >= maxTries) {
                      clearInterval(intervalId);
                      if (!allLoaded) console.warn('Max tries reached but some images are still processing.');
                  }
              })
              .catch(err => {
                  console.error('Error fetching vehicle images status:', err);
                  clearInterval(intervalId);
              });
      };

      fetchStatus();
      intervalId = setInterval(fetchStatus, interval);
      return () => clearInterval(intervalId);
  }

  // ----------------------------
  // Poll Images on Vehicle Index Page
  // ----------------------------
  function initPollVehicleImagesMyVehiclesPage() {
      const myVehiclesPage = document.getElementById('my-vehicles-page');
      if (!myVehiclesPage) return;

      initPollVehicleImages({
          onUpdate: vehicles => {
              vehicles.forEach(vehicle => {
                  const imgEl = document.querySelector(`img.primary-image[data-vehicle-id='${vehicle.vehicle_id}']`);
                  if (!imgEl) return;

                  // Only show real URL if completed
                  if (vehicle.primary_image.status === 'completed') {
                      imgEl.src = vehicle.primary_image.url || '/img/no_image.png';
                  } else if (vehicle.primary_image.status === 'pending' || vehicle.primary_image.status === 'processing') {
                      imgEl.src = '/img/loading.gif';
                  } else {
                      imgEl.src = '/img/no_image.png';
                  }
              });
          }
      });
  }

  // ----------------------------
  // Sortable Vehicle Images
  // ----------------------------
  function initSortableVehicleImages(wrapper) {
      if (!wrapper) return;

      const MAX_VALID = 12;
      const MAX_SIZE = 2 * 1024 * 1024; // 2MB
      const ordinals = [
          "Primary Image", "Second Image", "Third Image", "Fourth Image", "Fifth Image",
          "Sixth Image", "Seventh Image", "Eighth Image", "Ninth Image", "Tenth Image",
          "Eleventh Image", "Twelfth Image"
      ];

      // Determine component mode
      const mode = wrapper.dataset.mode;
      const currentVehicleId = wrapper.dataset.vehicleId;
      const initialImages = JSON.parse(wrapper.dataset.images || "[]");

      let draggedIndex = null;
      let tempIdCounter = 100000;
      let items = [];

      const list = wrapper.querySelector('#list');
      const fileInput = wrapper.querySelector('#fileInput');
      const submitBtn = wrapper.querySelector('#submitBtn');

      // --- Elements specific to each mode ---
      let payloadInput, form, mainFormImagePreviews, mainFormFileInput;

      if (mode === 'normal') {
          payloadInput = wrapper.querySelector('#payloadInput');
          form = wrapper.querySelector('#syncImagesForm');
          items = initialImages.map(img => ({
              id: parseInt(img.id, 10),
              image: (img.status === 'completed' && img.url) ? img.url : '/img/loading.gif',
              uiState: 'valid',
              vehicle_id: img.vehicle_id,
              original_filename: img.original_filename,
              status: img.status
          }));
      } else { // mode === 'modal'
          mainFormImagePreviews = document.querySelector('#imagePreviews');
          mainFormFileInput = document.querySelector('#vehicleFormImageUpload');

          // NEW: More robust function to load files from the main form
          const loadFromMainForm = () => {
              const files = Array.from(mainFormFileInput.files);

              // Handle case with no files
              if (files.length === 0) {
                  items = [];
                  renderList();
                  return;
              }

              // Use Promise.all to wait for all files to be read by FileReader
              const fileReadPromises = files.map(file => {
                  return new Promise((resolve) => {
                      const reader = new FileReader();
                      reader.onload = (e) => {
                          resolve({
                              id: tempIdCounter++,
                              image: e.target.result,
                              uiState: file.size > MAX_SIZE ? 'tooBig' : 'valid',
                              original_filename: file.name,
                              file,
                              status: ''
                          });
                      };
                      reader.readAsDataURL(file);
                  });
              });

              // Once all files are read, update the items and render the list once
              Promise.all(fileReadPromises).then(newItems => {
                  items = newItems;
                  renderList();
              });
          };

          // REPLACED: Ditching the MutationObserver for a simpler event listener
          window.addEventListener('open-image-modal', loadFromMainForm);
      }

      // ----------------------------
      // Render List (Unchanged)
      // ----------------------------
      function renderList() {
        list.innerHTML = '';
        const validItems = items.filter(i => i.uiState === 'valid');
        validItems.slice(MAX_VALID).forEach(i => i.uiState='tooMany');

        items.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'list-item';
            if(item.uiState === 'marked') div.classList.add('marked');
            if(item.uiState === 'tooMany') div.classList.add('too-many');
            if(item.uiState === 'tooBig') div.classList.add('too-big');
            if(item.uiState === 'duplicate') div.classList.add('too-big'); // Note: 'too-big' class is used for red ban icon
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

            // Position/Icons
            let posNumHTML = '';
            if(item.uiState === 'valid') posNumHTML = validItems.indexOf(item)+1;
            else if(item.uiState === 'marked') posNumHTML = `<i class="fa-solid fa-trash trash-icon"></i>`;
            else if(item.uiState === 'tooMany') posNumHTML = `<i class="fa-solid fa-ban ban-icon-amber"></i>`;
            else if(item.uiState === 'tooBig' || item.uiState === 'duplicate') posNumHTML = `<i class="fa-solid fa-ban ban-icon-red"></i>`;

            // Titles/Descriptions
            let title='', desc='';
            if(item.uiState === 'valid') {
                const pos = validItems.indexOf(item);
                title = ordinals[pos] || `${pos+1}th Image`;
                if(item.status ==='completed') desc="Uploaded and available.";
                else if(item.status ==='pending'||item.status==='processing') desc="Processing, please wait...";
                else if(item.status ==='failed') desc="Still processing, check back later.";
                else {
                  desc = "Ready to upload!";
                  item.readyToSubmit = true;
              }
            } else if(item.uiState==='marked') {
                title="Delete Image"; desc="Marked for deletion";
            } else if(item.uiState==='tooMany') {
                title="Too many images"; desc="This image will not be uploaded!";
            } else if(item.uiState==='tooBig') {
                title="Image too big"; desc="Images may not be more than 2MB.";
            } else if(item.uiState==='duplicate') {
                title="Duplicate image"; desc="This image is already in the list.";
            }
            const trashBtnClass =
                item.uiState==='marked' ? 'marked' :
                item.uiState==='tooMany' ? 'marked-amber' :
                (item.uiState==='tooBig'||item.uiState==='duplicate') ? 'marked' : '';
            div.innerHTML = `
                <i class="fa-solid fa-grip-vertical grip"></i>
                <div class="pos-num">${posNumHTML}</div>
                <img src="${item.image}" alt="${item.original_filename || 'preview'}">
                <div class="info"><h3>${title}</h3><p>${desc}</p></div>
                <div class="trash-btn ${trashBtnClass}"><i class="fa-solid fa-trash"></i></div>
            `;
            const trashBtn = div.querySelector('.trash-btn');
            if(item.uiState==='valid' || item.uiState==='marked') {
                trashBtn.addEventListener('click', () => {
                    if(item.uiState==='valid') {
                        item.uiState='marked';
                        const nextTooMany = items.find(i => i.uiState==='tooMany');
                        if(nextTooMany) nextTooMany.uiState='valid';
                    } else {
                        item.uiState='valid';
                        const validNow = items.filter(i => i.uiState==='valid');
                        if(validNow.length>MAX_VALID) validNow[MAX_VALID].uiState='tooMany';
                    }
                    renderList();
                });
            } else {
                trashBtn.style.opacity="0.5";
                trashBtn.style.cursor="not-allowed";
            }
            list.appendChild(div);
        });
        updateMarkedCount();
      }

      // ----------------------------
      // Update Marked Count (Unchanged)
      // ----------------------------
      function updateMarkedCount() {
        const markedCountEl = wrapper.querySelector('#markedCount');
        const tooManyCount = items.filter(i=>i.uiState==='tooMany').length;
        const tooBigCount = items.filter(i=>i.uiState==='tooBig').length;
        const duplicateCount = items.filter(i=>i.uiState==='duplicate').length;
        const markedCount = items.filter(i=>i.uiState==='marked').length;
        const readyCount = items.filter(i => i.uiState === 'valid' && !i.status).length;
        const parts = [];
        if(tooManyCount) parts.push(`There ${tooManyCount===1?'is':'are'} ${tooManyCount} item${tooManyCount>1?'s':''} too many`);
        if(tooBigCount) parts.push(`${tooBigCount} item${tooBigCount>1?'s':''} too big`);
        if(duplicateCount) parts.push(`${duplicateCount} duplicate${duplicateCount>1?'s':''}`);
        if(markedCount) parts.push(`${markedCount} item${markedCount>1?'s':''} marked for deletion`);
        if(readyCount) parts.push(`${readyCount} new item${readyCount>1?'s':''} ready`);
        markedCountEl.textContent = parts.join(', ') || 'No issues';
      }

      // ----------------------------
      // File Input Handler
      // ----------------------------
      fileInput.addEventListener('change', e => {
          const files = Array.from(e.target.files);
          files.forEach(file => {
              const validTypes = ['image/jpeg','image/png','image/jpg'];
              if(!validTypes.includes(file.type)) {
                  alert(`"${file.name}" is not supported. Only JPEG/PNG.`);
                  return;
              }
              const duplicate = items.some(i=>i.original_filename===file.name);
              const reader = new FileReader();
              reader.onload = () => {
                  items.push({
                      id: tempIdCounter++,
                      image: reader.result,
                      uiState: duplicate ? 'duplicate' : (file.size > MAX_SIZE ? 'tooBig' : 'valid'),
                      original_filename: file.name,
                      file, // The actual File object
                      status: ''
                  });
                  renderList();
              };
              reader.readAsDataURL(file);
          });
          // Clear the file input to allow re-selecting the same file if needed
          e.target.value = '';
      });

      // ----------------------------
      // Submit Handler (MODIFIED)
      // ----------------------------
      submitBtn.addEventListener('click', () => {
          if (mode === 'modal') {
              // --- MODAL SUBMIT LOGIC ---
              const validItems = items.filter(i => i.uiState === 'valid' && i.file);

              // 1. Create a DataTransfer object to hold the final files
              const dataTransfer = new DataTransfer();
              validItems.forEach(item => {
                  dataTransfer.items.add(item.file);
              });

              // 2. Update the main form's file input
              mainFormFileInput.files = dataTransfer.files;

              // 3. Update the image previews on the main form
              mainFormImagePreviews.innerHTML = '';
              validItems.forEach(item => {
                  const img = document.createElement('img');
                  img.src = item.image; // Use the base64 preview
                  mainFormImagePreviews.appendChild(img);
              });

              // 4. Reset component state for the next time it opens
              items = [];
              renderList();

              // 5. Dispatch event to close the modal (handled by Alpine.js)
              wrapper.dispatchEvent(new CustomEvent('close-modal', { bubbles: true, composed: true }));

          } else { // mode === 'normal'
              // --- NORMAL SUBMIT LOGIC (EXISTING) ---
              const payload = [];
              let order = 1;
              const validItems = items.filter(i=>i.uiState==='valid' || i.uiState==='marked');
              validItems.forEach(item => {
                  if(item.uiState==='marked' && item.vehicle_id) payload.push({id:item.id, action:'delete'});
                  else if(item.uiState==='valid' && !item.vehicle_id) payload.push({id:item.id, action:'upload', tempId:item.id});
                  else if(item.uiState==='valid' && item.vehicle_id) payload.push({id:item.id, action:'keep'});
                  if(item.uiState==='valid') payload[payload.length-1].position=order++;
              });

              payloadInput.value = JSON.stringify(payload);
              const dataTransfer = new DataTransfer();
              validItems.forEach(item=>{ if(item.file && item.uiState==='valid') dataTransfer.items.add(item.file); });
              fileInput.files = dataTransfer.files;
              form.submit();
          }
      });

      // ----------------------------
      // Poll backend image status (only for 'normal' mode)
      // ----------------------------
      if (mode === 'normal') {
          const updateImageStatusFromBackend = (vehicles) => {
              vehicles.forEach(vehicle => {
                  if (vehicle.vehicle_id != currentVehicleId) return;
                  vehicle.images.forEach(img => {
                      const item = items.find(i => i.id == img.id);
                      if (!item) return;
                      item.status = img.status;
                      if (img.status === 'completed' && img.url) {
                          item.image = img.url;
                      } else if (img.status === 'pending' || img.status === 'processing') {
                          item.image = '/img/loading.gif';
                      } else {
                          item.image = '/img/no_image.png';
                      }
                  });
              });
              renderList();
          };
          // This assumes initPollVehicleImages is a globally available function
          // If it's not, you may need to adjust how it's called.
          if(typeof initPollVehicleImages !== 'undefined') {
            initPollVehicleImages({ onUpdate: updateImageStatusFromBackend });
          }
      }

      // Initial Render
      renderList();
  }

  // ----------------------------
  // Initialize on page
  // ----------------------------
  document.querySelectorAll('.sortable-list-wrapper').forEach(wrapper => {
      initSortableVehicleImages(wrapper);
  });

  // ----------------------------
  // Start Alpine
  // ----------------------------
  window.Alpine = Alpine;
  Alpine.start();

  // ----------------------------
  // Initialize Functions
  // ----------------------------
  initSlider();
  initImagePicker();
  initMobileNavbar();
  imageCarousel();
  initMobileFilters();
  initCascadingDropdown('#manufacturerSelect', '#modelSelect');
  initCascadingDropdown('#provinceSelect', '#citySelect');
  initSortingDropdown();
  initAddToWatchlist();
  initShowPhoneNumber();
  initPollVehicleImagesMyVehiclesPage();
  initMileageInputs();

  // ----------------------------
  // Hero Slider Scroll Reveal
  // ----------------------------
  // Slider Title
  ScrollReveal().reveal(".hero-slide.active .hero-slider-title", {
    delay: 200,
    reset: true,
  });
  // Slider Content
  ScrollReveal().reveal(".hero-slide.active .hero-slider-content", {
    delay: 200,
    origin: "bottom",
    distance: "50%",
  });
});
