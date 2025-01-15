<template>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">
                {{ edit ? t('Edit Time Log') : t('Create Time Log') }}
            </h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <time-form :defaults="edit" :variant="edit ? 'edit' : undefined" />
    </div>
</template>
<script lang="ts" setup>
import type { TimeLog } from '@/types'

defineOptions({ name: 'TimeLogPage' })

const { t } = useI18n()

const props = defineProps<{
    edit?: TimeLog
}>()

const breadcrumbs = computed(() => [
    { href: route('dashboard'), title: t('Dashboard') },
    { href: route('time-log.index'), title: t('Time Logs') },
    {
        disabled: true,
        href: route(`time-log.${props.edit ? 'edit' : 'create'}`, {
            time_log: props.edit?.id,
        }),
        title: props.edit ? t('Edit') : t('Create'),
    },
])
</script>
