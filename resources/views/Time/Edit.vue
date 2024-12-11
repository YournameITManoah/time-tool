<script lang="ts" setup>
import { TimeLog } from '~/resources/types'

defineOptions({ name: 'TimeLogEdit' })

useHead({ title: 'Edit Time Log' })

defineProps<{
    timeLog: TimeLog
}>()
</script>

<template layout>
    <time-log-form :edit="timeLog" />
</template>
