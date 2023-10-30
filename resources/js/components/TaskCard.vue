<template>
    <div class="w-full bg-white text-gray-900 shadow-md rounded-lg p-3 pb-8 mb-4 relative"
        :class="task.completed_at || is_completion == 1 ? 'border-2 border-green-500' : 'text-gray-900'"
        @mouseenter="kanban.hoveredName = task.name" @mouseleave="kanban.unhoverTask()" @click="kanban.selectTask(task)">
        {{ task.name }}<br>
        <div class="text-xs text-gray-500 absolute bottom-5">{{ task.user.name }}</div>
        <div class="text-xs text-gray-500 absolute bottom-1">{{ task.completed_at }}</div>
        <img class="w-10 h-10 shadow-lg rounded-full absolute bottom-0 right-0 -mr-2 -mb-2 border-2 border-white"
            :src="getAvatar()" :alt="task.user.name" />
    </div>
</template>

<script setup>
// get the props
import { useKanbanStore } from '../stores/kanban';
import { sha256 } from 'js-sha256';
const kanban = useKanbanStore()

const getAvatar = function () {
    if (props.task.user.profile_picture_url !== null) {
        return props.task.user.profile_picture_url;
    } else {
        return ("https://www.gravatar.com/avatar/" + sha256(String(props.task.user.email).trim().toLowerCase()) + "?size=40");
    }
}

const props = defineProps({
    task: {
        type: Object,
        required: true
    },
    is_completion:{
        required:true
    }
})
</script>