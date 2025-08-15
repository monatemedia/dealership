<div
    x-data="flashMessages({{ json_encode($messages) }})"
    class="flash-container"
>
    <template x-for="msg in messages" :key="msg.id">
        <div
            :class="['flash-message', msg.cssClass]"
            x-show="msg.visible"
            x-transition
        >
            <i :class="msg.icon"></i>
            <span x-text="msg.text"></span>
            <button @click="dismiss(msg.id)">&times;</button>
        </div>
    </template>
</div>
