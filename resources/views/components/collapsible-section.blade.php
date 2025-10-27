{{-- resources/views/components/collapsible-section.blade.php --}}
@props([
    'title' => 'Additional Information',
    'open' => false,
    'storageKey' => null
])

<div
    class="collapsible-section"
    x-data="{
        isOpen: @js($open),
        storageKey: @js($storageKey),
        init() {
            if (this.storageKey) {
                const saved = localStorage.getItem(this.storageKey);
                if (saved !== null) {
                    this.isOpen = saved === 'true';
                }
            }
        },
        toggle() {
            this.isOpen = !this.isOpen;
            if (this.storageKey) {
                localStorage.setItem(this.storageKey, this.isOpen);
            }
        }
    }"
>
    <button
        type="button"
        class="collapsible-header"
        @click="toggle"
        :aria-expanded="isOpen.toString()"
    >
        <span>{{ $title }}</span>
        <svg
            class="chevron"
            :class="{ 'rotated': isOpen }"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="currentColor"
        >
            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
        </svg>
    </button>

    <div
        class="collapsible-content"
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0"
    >
        <div class="collapsible-inner">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
.collapsible-section {
    flex: 1 1 100%;
    width: calc(100% + 2rem);
    margin: 0 -1rem !important;
    border-top: 1px solid var(--border);
    overflow: hidden;
    background-color: var(--input-bg-color);
}

/* Remove gap between stacked collapsible sections */
.collapsible-section + .collapsible-section {
    margin-top: 0 !important;
    border-top: none;
}

/* Add bottom border only to the last collapsible section */
.collapsible-section:last-of-type {
    border-bottom: 1px solid var(--border);
}

.collapsible-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background-color: #f9fafb;
    border: none;
    color: var(--text-color);
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    font-family: var(--primary-font);
    transition: background-color 0.2s;
    text-align: left;
}

.collapsible-header:hover {
    background-color: #f3f4f6;
}

.collapsible-header .chevron {
    transition: transform 0.3s ease;
    flex-shrink: 0;
    color: var(--text-muted-color);
}

.collapsible-header .chevron.rotated {
    transform: rotate(180deg);
}

.collapsible-content {
    background-color: var(--input-bg-color);
}

.collapsible-inner {
    padding: 1.5rem 1.25rem;
}
</style>
