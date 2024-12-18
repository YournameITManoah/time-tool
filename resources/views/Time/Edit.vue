<template layout>
    <time-page :edit="timeLog" />
</template>
<script lang="ts" setup>
import { TimeLog } from '~/resources/types'

defineOptions({ name: 'TimeLogEdit' })

useHead({ title: 'Edit Time Log' })

defineProps<{
    timeLog: TimeLog
}>()
</script>
