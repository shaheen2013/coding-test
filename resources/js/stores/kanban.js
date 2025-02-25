import { defineStore } from 'pinia'

export const useKanbanStore = defineStore('kanban', {
  state: () => {
    return { 
        hoveredName: 'nothing',
        selectedTask: null,
        phases: [],
        users: [],
        creatingTask: false,
        editTask:false,
        creatingTaskProps: {
          name: '',
          phase_id: null,
          user_id: null,
        },
        editTaskProps: {
          id:'',
          name: '',
          phase_id: null,
          user_id: null,
        },
        phases_count:0,
        self: null,
    }
  },
  actions: {
    unhoverTask() {
      this.hoveredName = 'nothing'
    },
    selectTask(task) {
        this.selectedTask = task
    },
    unselectTask(task) {
        this.selectedTask = null
    },
    hasSelectedTask() {
        return this.selectedTask !== null
    },
  },
})