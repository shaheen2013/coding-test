<template>
    <div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex justify-content-center align-item-center">
                    <h2 class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }}</h2>
                    <h4 class=" ml-5 mt-1 text-white border-1 border-red-400 rounded" aria-hidden="true"> {{ count }}</h4>
                </div>

                <div class="text-right flex">
                    <PlusIcon @click="createTask()" class="h-6 w-6 text-white hover:cursor-pointer" aria-hidden="true" />
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <MenuButton
                                class="inline-flex w-full justify-center rounded-md px-4 pb-2 text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                </svg>

                            </MenuButton>
                        </div>
                        <transition
                            enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <MenuItems
                                class="absolute left-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-50"

                            >
                                <div class="px-1 py-1">
                                    <MenuItem v-slot="{ active }" @click="completedTask(props.phase_id)">
                                        <button
                                            :class="[
                active ? 'bg-violet-500 text-white' : 'text-gray-900',
                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
            ]"
                                        >
                                            <CheckIcon
                                                :active="active"
                                                class="mr-2 h-5 w-5 text-violet-400"
                                                aria-hidden="true"
                                            />
                                            {{ kanban.phases[props.phase_id].is_completion == 0?'Mark as Completion phase':'Mark as UnCompletion phase' }} 
                                        </button>
                                    </MenuItem>

                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
            
            <task-card v-if="kanban.phases[props.phase_id].tasks.length > 0"
                v-for="task in kanban.phases[props.phase_id].tasks" :task="task" :is_completion="kanban.phases[props.phase_id].is_completion" />
            <!-- A card to create a new task -->
            <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
                @click="createTask()">
                <span>Create a new task</span>
                <PlusIcon class="h-6 w-6" aria-hidden="true" />
            </div>
        </div>
    </div>
</template>

<script setup>
// get the props
import { useKanbanStore } from '../stores/kanban'
import { PlusIcon , CheckIcon } from '@heroicons/vue/20/solid'
import { ref, onMounted } from 'vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'


const kanban = useKanbanStore()
const count = ref(0);

const props = defineProps({
    phase_id: {
        type: Number,
        required: true
    },
})

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
}

const completedTask = async (id) => {
    try {
        const response = await axios.get(`/api/tasks/completed/${id}`);
        await refreshTasks();
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
}

const phaseCount = async () => {
    try {
        let id = props.phase_id;
        const response = await axios.get(`/api/tasks/count/${id}`);
        count.value = response.data;
    } catch (error) {
        console.error('There was an error fetching the task count!', error);
    }
}

const refreshTasks = async () => {
    try {
        const response = await axios.get('/api/tasks');
        const originalTasks = response.data;
        kanban.phases = originalTasks.reduce((acc, cur) => {
            acc[cur.id] = cur;
            return acc;
        }, {});
    } catch (error) {
        console.error('There was an error fetching the tasks!', error);
    }
}

onMounted( async () => {
    await phaseCount()
})


</script>

