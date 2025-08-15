import axios from 'axios';
import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener("DOMContentLoaded", function () {

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
          : m.type === 'warning'
          ? 'fa-solid fa-triangle-exclamation'
          : 'fa-solid fa-car-burst',
          cssClass: m.type === 'success'
          ? 'flash-success-message'
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

  // Make it flashMessages globally available for Alpine
  window.flashMessages = flashMessages;

  // ----------------------------
  // Hero Slider Initialization
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

  const initImagePicker = () => {
    const fileInput = document.querySelector("#carFormImageUpload");
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
      a.classList.add("car-form-image-preview");
      a.innerHTML = `
        <img src="${url}" />
      `;
      return a;
    }
  };

  const initMobileNavbar = () => {
    const btnToggle = document.querySelector(".btn-navbar-toggle");
    if (!btnToggle) return; // ← Guard

    btnToggle.onclick = () => {
      document.body.classList.toggle("navbar-opened");
    };
  };

  const imageCarousel = () => {
    const carousel = document.querySelector('.car-images-carousel');
    if (!carousel) return;

    const thumbnails = document.querySelectorAll('.car-image-thumbnails img');
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

  const initMobileFilters = () => {
    const filterButton = document.querySelector('.show-filters-button');
    const sidebar = document.querySelector('.search-cars-sidebar');
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
            // Make request on the URL to add or remove the car from watchlist
            axios.post(url).then((response) => {
                // Select both svg tags of the button
                const toShow = button.querySelector('svg.hidden');
                const toHide = button.querySelector('svg:not(.hidden)');
                // Which was hidden must be displayed
                toShow.classList.remove('hidden')
                // Which was displayed must be hidden
                toHide.classList.add('hidden')
                // Show alert to the user
                alert(response.data.message)
            })
            .catch(error => {
                // If error happened, we can log it to console
                console.error(error.response)
                // If error exists, and it has response with status 401
                if (error?.response?.status === 401) {
                    // Show alert to the user
                    alert("Please log in first to add cars into your watchlist.")
                } else {
                    alert("Internal Server Error. Please Try again later!")
                }
            })
        })
    })
  }

  const initShowPhoneNumber = () => {
    // Select the element we need to listen to click
    const span = document.querySelector('.car-details-phone-view');
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

// Load Thumbnail on My Cars Page after Image queue completes
const initMyCarsImageLoader = () => {
  const myCarsPage = document.getElementById('my-cars-page');
  if (!myCarsPage || !myCarsPage.dataset.checkImages) {
    return; // Not My Cars index page
  }

  let tries = 0;
  const maxTries = 12; // e.g., poll for 1 minute max
  let intervalId = null;

  function showBusyMessage() {
    const container = document.createElement('div');
    container.className = 'alert alert-warning';
    container.textContent = 'The website is very busy. Your images will be available soon.';
    document.querySelector('.container')?.prepend(container);
  }

  function updateCarImages() {
    console.log(`Polling attempt ${tries + 1}...`);

    axios.get('/api/my-cars/status')
      .then(response => {
        const cars = response.data;
        let allLoaded = true;

        cars.forEach(car => {
          const imgEl = document.querySelector(`img.primary-image[data-car-id='${car.id}']`);
          if (!imgEl) return;

          if (car.primary_image_status === 'pending' || car.primary_image_status === 'processing') {
            allLoaded = false;
            if (!imgEl.src.includes('loading.gif')) {
              imgEl.src = '/img/loading.gif';
            }
          }
          else if (car.primary_image_status === 'completed') {
            if (imgEl.src.includes('loading.gif') && car.primary_image_url) {
              imgEl.src = car.primary_image_url;
            }
          }
          else if (car.primary_image_status === 'failed') {
            if (imgEl.src.includes('loading.gif')) {
              imgEl.src = '/img/no_image.png';
            }
          }
        });

        if (allLoaded) {
          console.log('✅ All images loaded, stopping polling.');
          clearInterval(intervalId);
        }

        tries++;
        if (tries >= maxTries && !allLoaded) {
          console.log('⚠️ Max tries reached, stopping polling.');
          clearInterval(intervalId);
          showBusyMessage();
        }
      })
      .catch(error => {
        console.error('❌ Error fetching car images status:', error);
        clearInterval(intervalId);
      });
  }

  // First call immediately
  updateCarImages();

  // Then poll every 5 seconds
  intervalId = setInterval(updateCarImages, 5000);
};

  // Sortable List
//   const sortableList = () => {
//     const MAX_VALID = 12;
//     const MAX_SIZE = 2 * 1024 * 1024; // 2MB
//     const ordinals = ["Primary Image","Second Image","Third Image","Fourth Image","Fifth Image","Sixth Image","Seventh Image","Eighth Image","Ninth Image","Tenth Image","Eleventh Image","Twelfth Image"];

//     const items = [
//     { id: '1', image: 'https://images.pexels.com/photos/1562/italian-landscape-mountains-nature.jpg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop', state: 'valid' },
//     { id: '2', image: 'https://images.pexels.com/photos/416676/pexels-photo-416676.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop', state: 'valid' },
//     { id: '3', image: 'https://images.pexels.com/photos/147411/italy-mountains-dawn-daybreak-147411.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop', state: 'valid' }
//     ];

//     let draggedIndex = null;

//     function renderList() {
//       const list = document.getElementById('list');
//       list.innerHTML = '';

//       const validItems = items.filter(i => i.state === 'valid');
//       validItems.forEach((item, idx) => {
//         if (idx < MAX_VALID) {
//           item.state = 'valid';
//         } else {
//           item.state = 'tooMany';
//         }
//       });

//       items.forEach((item, index) => {
//         const div = document.createElement('div');
//         div.className = 'list-item';
//         if (item.state === 'marked') div.classList.add('marked');
//         if (item.state === 'tooMany') div.classList.add('too-many');
//         if (item.state === 'tooBig') div.classList.add('too-big');
//         div.draggable = true;

//         div.addEventListener('dragstart', () => { draggedIndex = index; div.classList.add('dragging'); });
//         div.addEventListener('dragend', () => { draggedIndex = null; div.classList.remove('dragging'); });
//         div.addEventListener('dragover', (e) => { e.preventDefault(); div.classList.add('over'); });
//         div.addEventListener('dragleave', () => div.classList.remove('over'));
//         div.addEventListener('drop', () => { const draggedItem = items.splice(draggedIndex, 1)[0]; items.splice(index, 0, draggedItem); renderList(); });

//         let posNumHTML = '';
//         if (item.state === 'valid') {
//           const pos = validItems.indexOf(item);
//           posNumHTML = pos === -1 ? '' : (pos+1);
//         } else if (item.state === 'marked') {
//           posNumHTML = `<i class="fa-solid fa-trash trash-icon"></i>`;
//         } else if (item.state === 'tooMany') {
//           posNumHTML = `<i class="fa-solid fa-ban ban-icon-amber"></i>`;
//         } else if (item.state === 'tooBig') {
//           posNumHTML = `<i class="fa-solid fa-ban ban-icon-red"></i>`;
//         }

//         let title = '', desc = '';
//         if (item.state === 'valid') {
//           const pos = validItems.indexOf(item);
//           title = ordinals[pos] || `${pos+1}th Image`;
//           desc = "Ready to submit!";
//         } else if (item.state === 'marked') {
//           title = "Delete Image";
//           desc = "Marked for deletion";
//         } else if (item.state === 'tooMany') {
//           title = "Too many images";
//           desc = "This image will not be uploaded!";
//         } else if (item.state === 'tooBig') {
//           title = "Image size is too big";
//           desc = "Images may not be more than 2MB";
//         }

//         const trashBtnClass =
//         item.state === 'marked' || item.state === 'tooBig' ? 'marked'
//         : item.state === 'tooMany' ? 'marked-amber'
//         : '';

//         div.innerHTML = `
//         <i class="fa-solid fa-grip-vertical grip"></i>
//         <div class="pos-num">${posNumHTML}</div>
//         <img src="${item.image}" alt="">
//         <div class="info">
//           <h3>${title}</h3>
//           <p>${desc}</p>
//         </div>
//         <div class="trash-btn ${trashBtnClass}">
//           <i class="fa-solid fa-trash"></i>
//         </div>
//         `;

//         div.querySelector('.trash-btn').addEventListener('click', () => {
//         if (item.state === 'valid') {
//           item.state = 'marked';
//           promoteTooMany();
//         } else if (item.state === 'marked') {
//           item.state = 'valid';
//         } else {
//           return; // tooMany / tooBig cannot toggle
//         }
//         renderList();
//       });

//       list.appendChild(div);
//       });

//       updateMarkedCount();
//     }

//     function promoteTooMany() {
//       const tooManyIndex = items.findIndex(i => i.state === 'tooMany');
//       if (tooManyIndex !== -1) {
//         items[tooManyIndex].state = 'valid';
//       }
//     }

//     function updateMarkedCount() {
//       const tooManyCount = items.filter(i => i.state === 'tooMany').length;
//       const tooBigCount = items.filter(i => i.state === 'tooBig').length;
//       const markedCount = items.filter(i => i.state === 'marked').length;
//       const parts = [];
//       if (tooManyCount > 0) parts.push(`There ${tooManyCount === 1 ? 'is' : 'are'} ${tooManyCount} item${tooManyCount>1?'s':''} too many`);
//       if (tooBigCount > 0) parts.push(`${tooBigCount} item${tooBigCount>1?'s':''} ${tooBigCount===1?'is':'are'} too big`);
//       if (markedCount > 0) parts.push(`${markedCount} item${markedCount>1?'s':''} marked for deletion`);
//       document.getElementById('markedCount').textContent = parts.join(', ') || 'No issues';
//     }

//     document.getElementById('fileInput').addEventListener('change', (e) => {
//       const files = e.target.files;
//       Array.from(files).forEach(file => {
//         // ✅ Only accept jpeg, jpg, png
//         const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
//         if (!validTypes.includes(file.type)) {
//           alert(`"${file.name}" is not a supported format. Please upload only JPEG or PNG images.`);
//         return;
//         }

//         const state = file.size > MAX_SIZE ? 'tooBig' : 'valid';
//         const reader = new FileReader();
//         reader.onload = () => {
//           items.push({
//             id: Date.now().toString(),
//             image: reader.result,
//             state
//           });
//         renderList();
//         };
//         reader.readAsDataURL(file);
//       });
//     });

//     document.getElementById('submitBtn').addEventListener('click', () => {
//       console.log('Submitting list', {
//         allItems: items,
//         markedForDeletion: items.filter(i => i.state === 'marked'),
//         remaining: items.filter(i => i.state === 'valid')
//       });
//       alert('List submitted! Check console for details.');
//     });

//     renderList();
//   }

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
  initMyCarsImageLoader();
//   sortableList();
  // ----------------------------
  // Start Alpine
  // ----------------------------
  window.Alpine = Alpine;
  Alpine.start();

  ScrollReveal().reveal(".hero-slide.active .hero-slider-title", {
    delay: 200,
    reset: true,
  });
  ScrollReveal().reveal(".hero-slide.active .hero-slider-content", {
    delay: 200,
    origin: "bottom",
    distance: "50%",
  });
});
