<template layout>
    <time-page />
</template>
<script lang="ts" setup>
defineOptions({ name: 'TimeLogCreate' })

const { t } = useI18n()

useHead({ title: t('Create Time Log') })
</script>
