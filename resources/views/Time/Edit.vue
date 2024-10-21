<script lang="ts" setup>
defineOptions({ name: 'TimeLogEdit' })

useHead({ title: 'Edit Time Log' })

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Time Logs',
        href: route('time-log.index'),
    },
    {
        title: 'Edit',
        href: route('time-log.edit'),
        disabled: true,
    },
]
</script>

<template layout>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">Edit Time Log</h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <v-card>
            <v-card-text>
                <v-card-title> Edit Time Log </v-card-title>
            </v-card-text>
        </v-card>
    </div>
</template>
