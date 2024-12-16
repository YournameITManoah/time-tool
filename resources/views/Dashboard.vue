<template layout>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">Dashboard</h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <v-card>
            <v-card-text>
                <v-card-title> Welcome back, {{ user?.name }}! </v-card-title>
            </v-card-text>
        </v-card>
    </div>
</template>
<script lang="ts" setup>
defineOptions({ name: 'DashboardPage' })

useHead({ title: 'Dashboard' })

const user = useProperty('auth.user')

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        disabled: true,
    },
]
</script>
