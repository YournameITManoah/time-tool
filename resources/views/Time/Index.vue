<!-- eslint-disable vue/valid-v-slot -->
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
                    hover
                    @click:row="onRowClick"
                    @update:options="loadItems"
                >
                    <template #top>
                        <v-row no-gutters>
                            <v-spacer />
                            <router-link
                                :href="route('time-logs.create')"
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
                    <template #item.comments="{ item }">
                        <div
                            class="text-truncate"
                            :style="`min-width: 93px; max-width: ${mdAndDown ? 10 : lgAndDown ? 20 : 40}vw`"
                        >
                            {{ item.comments }}
                        </div>
                    </template>
                    <template #item.actions="{ item }">
                        <router-link
                            :href="
                                route('time-logs.edit', { time_log: item.id })
                            "
                            :aria-label="t('Edit Time Log')"
                        >
                            <v-btn
                                tabindex="-1"
                                size="small"
                                variant="text"
                                :disabled="removing === item.id"
                                icon="mdi-pencil"
                            />
                        </router-link>
                        <v-btn
                            size="small"
                            variant="text"
                            color="error"
                            icon="mdi-delete"
                            :loading="removing === item.id"
                            @click.stop="removeTimeLog(item.id)"
                        />
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
    VDataTableRow,
} from '@/types'

import { useDisplay } from 'vuetify'

import { emitter } from '~/resources/application/mitt'

defineOptions({ name: 'TimeLogIndex' })

const { t } = useI18n()
const { lgAndDown, mdAndDown } = useDisplay()

useHead({ title: t('Time Logs') })

const { formatDate, formatDuration } = useDate()

const breadcrumbs = computed(() => [
    {
        href: route('dashboard'),
        title: t('Dashboard'),
    },
    {
        current: true,
        href: route('time-logs.index'),
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
        { key: 'comments', sortable: false, title: t('Comments') },
        { key: 'actions', sortable: false, title: t('Actions') },
    ]
        .filter((h) => h.key !== groupBy.value[0]?.key)
        .map((h) => ({ ...h, headerProps: { class: 'font-weight-bold' } }))
})

const items = ref<TimeLogExtended[]>([])
const totalItems = ref(0)
const isLoadingTable = ref(false)
const search = ref<string | undefined>()

const removing = ref(0)
const removeTimeLog = async (id: number) => {
    removing.value = id
    try {
        await router.navigate({
            method: 'DELETE',
            url: route('time-logs.destroy', { time_log: id }),
        })
    } catch (e) {
        console.error(e)
    } finally {
        removing.value = 0
    }
}

const onRowClick = async (
    _e: PointerEvent,
    row: VDataTableRow<(typeof headers.value)[0], TimeLogExtended>,
) => {
    router.navigate({ url: route('time-logs.edit', { time_log: row.item.id }) })
}

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
            route('api.time-logs.index'),
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
