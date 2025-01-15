<template layout>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">Dashboard</h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <v-card>
            <v-card-text>
                <v-card-title>
                    {{ t('messages.welcome_back', { name: user?.name }) }}
                </v-card-title>
            </v-card-text>
        </v-card>
    </div>
</template>
<script lang="ts" setup>
defineOptions({ name: 'DashboardPage' })

const { t } = useI18n()

useHead({ title: t('Dashboard') })

const user = useProperty('auth.user')

const breadcrumbs = computed(() => [
    {
        disabled: true,
        href: route('dashboard'),
        title: t('Dashboard'),
    },
])
</script>
