/**
 * resources/js/search/SearchRangeSlider.js
 *
 * Pure Vanilla JavaScript implementation of the Range Slider.
 * Dependency Free. No jQuery. No Alpine.
 */

export class SearchRangeSlider {
    constructor(container) {
        this.container = container;

        // Configuration from Data Attributes
        this.name = container.dataset.name || 'range_km';
        this.maxRangeDefault = parseInt(container.dataset.maxRange) || 1000;
        this.cityIdKey = 'geo_filter_city_id';
        this.rangeKey = 'geo_filter_range_km';

        // Elements
        this.input = container.querySelector('input[type="range"]');
        // Hidden input should have an ID matching the convention used by VehicleInstantSearch
        this.hiddenInput = document.getElementById(`${this.name}_filter`) || container.querySelector('input[type="hidden"]');
        this.displayValue = container.querySelector('.range-value-display');
        this.maxDisplay = container.querySelector('.range-max-display');
        this.loader = container.querySelector('.range-loader');

        // State
        this.currentCityId = null;

        // Initialize
        this.init();
    }

    init() {
        // 1. Set initial max range and get saved range
        this.updateMax(this.maxRangeDefault);

        const savedRange = localStorage.getItem(this.rangeKey);
        const initialValue = savedRange ? parseInt(savedRange) : 5;

        // 2. Load city state and set range
        const cityIdFromLS = localStorage.getItem(this.cityIdKey);
        if (cityIdFromLS && cityIdFromLS !== '0' && cityIdFromLS !== 'null') {
            this.currentCityId = cityIdFromLS;
            // Fetch max range first, then set initial range (which handles clamping)
            this.fetchMaxRange(this.currentCityId, initialValue);
        } else {
            // Set initial range based on LS or default (5), clamped by default max
            this.setRange(initialValue);
        }

        // 3. Bind Events
        this.attachEvents();

        console.log(`✅ SearchRangeSlider initialized for ${this.name}`);
    }

    attachEvents() {
        // Input Event (Drag): Update display and hidden input immediately
        this.input.addEventListener('input', (e) => {
            const value = parseInt(e.target.value);
            this.updateUI(value);
            this.dispatchChange(value);
        });

        // Window Event: City Selected (Dispatched by SearchCity component)
        window.addEventListener('city-selected', (e) => {
            console.log('👂 Range Slider received city-selected event.'); // <--- DIAGNOSTIC LOG
            const cityId = e.detail.id;
            this.currentCityId = cityId;
            // When city changes, reset the range to 5, and fetch new max
            this.fetchMaxRange(cityId, 5);
        });

        // Window Event: Reset Filters (Dispatched by Sidebar/Modal)
        window.addEventListener('filters-reset', () => {
            this.reset();
        });
    }

    /**
     * Sets the slider value, ensuring it is clamped between min (5) and max.
     * @param {number} value - The desired range value.
     */
    setRange(value) {
        const min = 5;
        const max = parseInt(this.input.max);

        let safeValue = parseInt(value);

        if (isNaN(safeValue)) safeValue = min;

        // 🔑 CRITICAL FIX: Clamp the value to be within the current min/max limits
        if (safeValue > max) safeValue = max;
        if (safeValue < min) safeValue = min;

        this.input.value = safeValue;
        this.updateUI(safeValue);
    }

    updateUI(value) {
        // Update text display
        if (this.displayValue) this.displayValue.textContent = `${value} km`;

        // Update hidden input for form submission/InstantSearch
        if (this.hiddenInput) {
            this.hiddenInput.value = value;
            // Manually trigger change event on hidden input if needed by other listeners
            this.hiddenInput.dispatchEvent(new Event('change'));
        }
    }

    dispatchChange(value) {
        // Update Local Storage
        localStorage.setItem(this.rangeKey, value);

        // Dispatch event for Alpine parents (Sidebar/Modal) to catch
        window.dispatchEvent(new CustomEvent('range-changed', {
            detail: { range: value }
        }));
    }

    /**
     * Fetches the maximum possible range for a given city and updates the slider limits.
     * @param {string|number} cityId - The ID of the origin city.
     * @param {number} rangeToSet - The value to set the handle to after max is determined.
     */
    async fetchMaxRange(cityId, rangeToSet) {
        console.log(`📡 Fetching max range for city ID: ${cityId}. Target range: ${rangeToSet}`); // <--- DIAGNOSTIC LOG

        if (!cityId || cityId === '0' || cityId === 'null') {
            this.updateMax(this.maxRangeDefault); // Reset to default max
            this.setRange(rangeToSet);
            return;
        }

        this.toggleLoader(true);

        try {
            const response = await fetch(`/api/vehicles/max-range/${cityId}`);
            if (!response.ok) throw new Error('Failed to fetch max range');
            const data = await response.json();
            const newMax = data.max_range_km || 1500;

            this.updateMax(newMax);

            // Re-apply the desired range, which will automatically clamp it
            // if rangeToSet is now > newMax (e.g., if we were at 800 and max became 500)
            this.setRange(rangeToSet);
            this.dispatchChange(this.input.value); // Dispatch the final, clamped value

        } catch (error) {
            console.error('Error fetching max range:', error);
            // On error, revert to a large, safe default and ensure range is clamped
            this.updateMax(this.maxRangeDefault);
            this.setRange(rangeToSet);
            this.dispatchChange(this.input.value);
        } finally {
            this.toggleLoader(false);
        }
    }

    updateMax(newMax) {
        const max = Math.round(newMax);
        this.input.max = max;
        if (this.maxDisplay) this.maxDisplay.textContent = `${max} km`;
    }

    toggleLoader(show) {
        if (this.loader) {
            this.loader.style.display = show ? 'inline' : 'none';
        }
    }

    reset() {
        console.log('🔄 Resetting Range Slider');
        this.currentCityId = null;
        localStorage.removeItem(this.rangeKey);
        this.updateMax(this.maxRangeDefault); // Reset max limit
        this.setRange(5); // Reset handle to 5
        this.dispatchChange(5);
    }
}
