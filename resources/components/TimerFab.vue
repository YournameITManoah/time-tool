<template>
    <v-fab location="bottom end" color="primary" icon size="x-large" app appear>
        <template v-if="duration">{{ duration }}</template>
        <v-icon v-else icon="mdi-timer" />
        <v-menu
            v-model="menu"
            activator="parent"
            :close-on-content-click="false"
        >
            <time-form
                :defaults="timeLog"
                variant="timer"
                style="max-width: 400px"
                @discard="resetTimer"
                @start="startTimer"
            />
        </v-menu>
    </v-fab>
</template>
<script setup lang="ts">
import { useTimerStore } from '@/stores/timer'
import { storeToRefs } from 'pinia'

// Application name
const name = import.meta.env.VITE_APP_NAME

const { t } = useI18n()

// Persisted state
const { startTime, timeLog } = storeToRefs(useTimerStore())

// Local State
const menu = ref(false)
const duration = ref('')
const interval = ref<null | number>(null)
const date = new Date().toLocaleDateString('en-CA')

onMounted(() => {
    if (startTime.value && timeLog.value.project_id && timeLog.value.task_id) {
        startTimer(
            timeLog.value.project_id,
            timeLog.value.task_id,
            startTime.value,
        )
    }
})

// Calculate how many minutes and seconds the timer has been running
const calculateDuration = () => {
    if (!startTime.value) return
    const diff = new Date().getTime() - startTime.value.getTime()
    const secs = Math.floor((diff / 1000) % 60)
    const mins = Math.floor(diff / 60000)
    duration.value = `${pad(mins)}:${pad(secs)}`
    document.title = `${t('Working')}: ${duration.value}`
}

// Start the timer
const startTimer = (project: number, task: number, start?: Date) => {
    // Set time log properties
    startTime.value = start ?? new Date()
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
    const msg = t('messages.timer_running')
    e.returnValue = msg
    return msg
}

// Cleanup
onBeforeUnmount(() => {
    resetTimer()
})
</script>
