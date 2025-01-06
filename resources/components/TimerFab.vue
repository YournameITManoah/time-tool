<template>
    <v-fab location="bottom end" color="primary" icon size="x-large" app appear>
        <template v-if="duration">{{ duration }}</template>
        <v-icon v-else icon="mdi-clock" />
        <v-menu
            v-model="menu"
            activator="parent"
            :close-on-content-click="false"
        >
            <time-form
                :defaults="timeLog"
                variant="timer"
                style="max-width: 400px"
                @cancel="resetTimer"
                @start="startTimer"
            />
        </v-menu>
    </v-fab>
</template>
<script setup lang="ts">
import type { TimeLog } from '@/types'

// Application name
const name = import.meta.env.VITE_APP_NAME

// State
const menu = ref(false)
const duration = ref('')
const interval = ref<number | null>(null)
const startTime = ref<Date | null>(null)
const date = new Date().toLocaleDateString('en-CA')
const timeLog = ref<Partial<TimeLog>>({ date })

// Calculate how many minutes and seconds the timer has been running
const calculateDuration = () => {
    if (!startTime.value) return
    const diff = new Date().getTime() - startTime.value.getTime()
    const secs = Math.floor((diff / 1000) % 60)
    const mins = Math.floor(diff / 60000)
    duration.value = `${pad(mins)}:${pad(secs)}`
    document.title = `Working: ${duration.value}`
}

// Start the timer
const startTimer = (project: number, task: number) => {
    // Set time log properties
    startTime.value = new Date()
    timeLog.value.start_time = startTime.value.toLocaleTimeString('nl')
    timeLog.value.project_id = project
    timeLog.value.task_id = task

    // (re)start interval
    if (interval.value !== null) window.clearInterval(interval.value)
    interval.value = window.setInterval(calculateDuration, 1000)

    // Close timer menu
    menu.value = false

    // Add close tab confirmation message
    window.addEventListener('beforeunload', beforeUnloadHandler)
}

// Reset timer to default values
const resetTimer = () => {
    if (interval.value) {
        window.clearInterval(interval.value)
        interval.value = null
        document.title = name
    }
    startTime.value = null
    duration.value = ''
    timeLog.value = { date }
    menu.value = false
    window.removeEventListener('beforeunload', beforeUnloadHandler)
}

// Ask confirmation before closing the tab
const beforeUnloadHandler = (e: BeforeUnloadEvent) => {
    e.preventDefault()
    e.returnValue = 'You still have a timer running. Are you sure?'
    return 'You still have a timer running. Are you sure?'
}

// Cleanup
onBeforeUnmount(() => {
    resetTimer()
})
</script>
