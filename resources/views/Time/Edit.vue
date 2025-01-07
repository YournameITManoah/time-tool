<template layout>
    <time-page :edit="timeLog" />
</template>
<script lang="ts" setup>
import type { TimeLog } from '@/types'

defineOptions({ name: 'TimeLogEdit' })

const { t } = useI18n()

useHead({ title: t('Edit Time Log') })

defineProps<{
    timeLog: TimeLog
}>()
</script>
