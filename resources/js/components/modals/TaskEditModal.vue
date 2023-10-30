<template>
    <div class="relative">
        <span class="w-6 h-6 absolute top-0 right-0 hover:cursor-pointer" @click="kanban.unselectTask()"> &#x2715; </span>
        <div class="mt-3 sm:mt-2">

            <DialogTitle as="h3" class="mb-6 text-base font-semibold leading-6 text-gray-900">Edit Task
            </DialogTitle>
            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Task
                    description {{ kanban.editTaskProps.id }}</label>
                <div class="relative mt-2">
                    <input type="text" id="name" v-model="kanban.editTaskProps.name"
                        class="peer block w-full border-0 bg-gray-50 py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6"
                        placeholder="Make it productive, but also fun!" />
                    <div class="absolute inset-x-0 bottom-0 border-t border-gray-300 peer-focus:border-t-2 peer-focus:border-blue-600"
                        aria-hidden="true" />
                </div>
            </div>

            <div class="mt-8">
                <label for="taskPhase" class="block text-sm font-medium leading-6 text-gray-900">Assignee</label>
                <select v-model="kanban.editTaskProps.user_id" id="taskPhase"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                    <option v-for="persion in kanban.users" :key="persion.id" :value="persion.id">{{ persion.name
                    }}</option>
                </select>
            </div>


            <div class="mt-8">
                <label for="taskPhase" class="block text-sm font-medium leading-6 text-gray-900">Phase</label>
                <select v-model="kanban.editTaskProps.phase_id" id="taskPhase"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                    <option v-for="phase in kanban.phases" :key="phase.id" :value="phase.id">{{ phase.name
                    }}</option>
                </select>
            </div>
        </div>

        <div class="mt-5 sm:mt-6">
            <button type="button"
                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                @click="updateCard()">Edit
                the card!</button>
        </div>
    </div>
</template>

<script setup>
import { CheckIcon, ChevronUpDownIcon, TrashIcon } from '@heroicons/vue/20/solid'
import { useKanbanStore } from '../../stores/kanban'
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { DialogTitle, Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'

const errors = ref(null)
const kanban = useKanbanStore();

const updateCard = async () => {
    try {
        const response = await axios.put(`/api/tasks/${kanban.editTaskProps.id}`, {
            name: kanban.editTaskProps.name,
            phase_id: kanban.editTaskProps.phase_id,
            user_id: kanban.editTaskProps.user_id,
        });
        await refreshTasks();
        kanban.editTask = false
        kanban.unselectTask();
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
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
</script>

