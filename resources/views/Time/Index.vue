<template layout>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">{{ t('Time Logs') }}</h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <v-card>
            <v-card-text>
                <v-data-table-server
                    v-model:group-by="groupBy"
                    :headers="headers"
                    :items="items"
                    :items-length="totalItems"
                    :loading="isLoadingTable"
                    :search="search"
                    @update:options="loadItems"
                >
                    <template #top>
                        <v-row no-gutters>
                            <v-spacer />
                            <router-link
                                :href="route('time-log.create')"
                                :aria-label="t('Create Time Log')"
                            >
                                <v-btn
                                    color="primary"
                                    prepend-icon="mdi-plus"
                                    tabindex="-1"
                                    aria-hidden="true"
                                    :text="t('Create Time Log')"
                                />
                            </router-link>
                        </v-row>
                    </template>
                    <template #item.date="{ value }">
                        {{ formatDate(value) }}
                    </template>
                    <template #item.start_time="{ value }">
                        {{ formatTime(value) }}
                    </template>
                    <template #item.stop_time="{ value }">
                        {{ formatTime(value) }}
                    </template>
                    <template #item.duration="{ item }">
                        {{ formatDuration(item.start_time, item.stop_time) }}
                    </template>
                    <template #item.actions="{ item }">
                        <router-link
                            :href="
                                route('time-log.edit', { time_log: item.id })
                            "
                            :aria-label="t('Edit Time Log')"
                        >
                            <v-btn
                                tabindex="-1"
                                size="small"
                                aria-hidden="true"
                                variant="text"
                                icon="mdi-pencil"
                            />
                        </router-link>
                    </template>
                </v-data-table-server>
            </v-card-text>
        </v-card>
    </div>
</template>
<script lang="ts" setup>
import type {
    PagedParams,
    PagedResult,
    TimeLogExtended,
    VDataTableOptions,
} from '@/types'

import { emitter } from '~/resources/application/mitt'

defineOptions({ name: 'TimeLogIndex' })

const { t } = useI18n()

useHead({ title: t('Time Logs') })

const { formatDate, formatDuration } = useDate()

const breadcrumbs = computed(() => [
    {
        href: route('dashboard'),
        title: t('Dashboard'),
    },
    {
        disabled: true,
        href: route('time-log.index'),
        title: t('Time Logs'),
    },
])

const groupBy = ref<{ key: string; title: string }[]>([])
const headers = computed(() => {
    return [
        { key: 'project.name', title: t('Project') },
        { key: 'task.name', title: t('Task') },
        { key: 'date', title: t('Date') },
        { key: 'start_time', title: t('Start time') },
        { key: 'stop_time', title: t('Stop time') },
        { key: 'duration', sortable: false, title: t('Duration') },
        { key: 'actions', sortable: false, title: t('Actions') },
    ]
        .filter((h) => h.key !== groupBy.value[0]?.key)
        .map((h) => ({ ...h, headerProps: { class: 'font-weight-bold' } }))
})

const items = ref<TimeLogExtended[]>([])
const totalItems = ref(0)
const isLoadingTable = ref(false)
const search = ref<string | undefined>()

const loadItems = async ({
    itemsPerPage,
    page,
    search,
    sortBy,
}: VDataTableOptions) => {
    isLoadingTable.value = true
    const params: PagedParams = {
        limit: itemsPerPage,
        page,
        sort: sortBy[0],
    }

    if (search) params.search = search

    try {
        const result = await window.axios<PagedResult<TimeLogExtended>>(
            '/api/time-log',
            { params },
        )
        items.value = result.data.data
        totalItems.value = result.data.total
    } catch (e) {
        console.error(e)
    } finally {
        isLoadingTable.value = false
    }
}

emitter.on('time-log:refresh', () => {
    search.value = Date.now().toString()
})
</script>
