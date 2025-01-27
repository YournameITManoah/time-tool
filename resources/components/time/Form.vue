<template>
    <v-card>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col
                            :cols="sizes.cols"
                            :sm="sizes.sm"
                            :md="sizes.md"
                            :lg="sizes.lg"
                        >
                            <v-select
                                v-model="form.fields.project_id"
                                :label="t('Project')"
                                :items="projects"
                                :error-messages="form.errors.project_id"
                                :loading="fetchingConnections"
                                :readonly="timerActive"
                                prepend-icon="mdi-cards-variant"
                                item-title="name"
                                item-value="id"
                                :rules="[isRequired]"
                                required
                                @update:model-value="
                                    form.fields.task_id = undefined
                                "
                            >
                                <template #item="{ props: itemProps, item }">
                                    <v-list-item
                                        v-bind="itemProps"
                                        :subtitle="item.raw.client.name"
                                    />
                                </template>
                            </v-select>
                        </v-col>
                        <v-col
                            :cols="sizes.cols"
                            :sm="sizes.sm"
                            :md="sizes.md"
                            :lg="sizes.lg"
                        >
                            <v-select
                                v-model="form.fields.task_id"
                                :label="t('Task')"
                                :items="tasks"
                                :error-messages="form.errors.task_id"
                                prepend-icon="mdi-format-list-bulleted"
                                :readonly="timerActive"
                                item-title="name"
                                item-value="id"
                                :rules="[isRequired]"
                                required
                            />
                        </v-col>
                        <!-- If not timer, show date/time fields -->
                        <template v-if="variant !== 'timer'">
                            <v-col
                                :cols="sizes.cols"
                                :sm="sizes.sm"
                                :md="sizes.md"
                                :lg="sizes.lg"
                            >
                                <field-date
                                    v-model="form.fields.date"
                                    :label="t('Date')"
                                    :min="lastYear.toISOString()"
                                    :max="today"
                                    :rules="[isRequired]"
                                    :disabled="form.processing"
                                    :error-messages="form.errors.date"
                                    required
                                />
                            </v-col>
                            <v-col
                                :cols="sizes.cols"
                                :sm="sizes.sm"
                                :md="sizes.md"
                                :lg="sizes.lg"
                            >
                                <field-time
                                    v-model="form.fields.start_time"
                                    :label="t('Start time')"
                                    :max="form.fields.stop_time"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.start_time"
                                />
                            </v-col>
                            <v-col
                                :cols="sizes.cols"
                                :sm="sizes.sm"
                                :md="sizes.md"
                                :lg="sizes.lg"
                            >
                                <field-time
                                    v-model="form.fields.stop_time"
                                    :label="t('Stop time')"
                                    :min="form.fields.start_time"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.stop_time"
                                />
                            </v-col>
                            <v-col
                                v-if="duration"
                                :cols="sizes.cols"
                                :sm="sizes.sm"
                                :md="sizes.md"
                                :lg="sizes.lg"
                            >
                                <v-text-field
                                    :model-value="duration"
                                    readonly
                                    :label="t('Duration')"
                                    prepend-icon="mdi-timer-sand"
                                />
                            </v-col>
                        </template>
                        <v-col
                            :cols="sizes.cols"
                            :sm="sizes.sm"
                            :md="sizes.md"
                            :lg="sizes.lg"
                        >
                            <field-textarea
                                v-model="form.fields.comments"
                                :label="t('Comments')"
                                :readonly="timerActive"
                                :clearable="!timerActive"
                                :error-messages="form.errors.comments"
                                prepend-icon="mdi-comment-outline"
                                :max="200"
                            />
                        </v-col>
                    </v-row>
                    <v-row v-if="variant === 'timer'">
                        <v-alert
                            v-if="timerErrors.length"
                            type="error"
                            color="error"
                            variant="outlined"
                            :title="t('Errors')"
                        >
                            <ul class="pl-5">
                                <li
                                    v-for="timerError in timerErrors"
                                    :key="timerError"
                                >
                                    {{ timerError }}
                                </li>
                            </ul>
                        </v-alert>
                    </v-row>
                    <v-row v-else>
                        <v-spacer />
                        <router-link
                            :disabled="form.processing"
                            :href="route('time-logs.index')"
                            :aria-label="t('Cancel')"
                        >
                            <v-btn
                                :disabled="form.processing"
                                color="error"
                                class="mr-4"
                                tabindex="-1"
                                aria-hidden="true"
                                :text="t('Cancel')"
                            />
                        </router-link>
                        <v-btn
                            :loading="form.processing"
                            color="primary"
                            type="submit"
                            :text="variant === 'edit' ? t('Edit') : t('Create')"
                        />
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions v-if="variant === 'timer'">
                <dialog-confirm
                    v-model="confirmDiscard"
                    :message="t('messages.confirm_timer_discard')"
                    :confirm-label="t('Discard')"
                    :on-confirm="() => emit('discard')"
                />
                <v-btn
                    v-if="timerActive"
                    color="error"
                    :disabled="form.processing"
                    prepend-icon="mdi-cancel"
                    :text="t('Discard')"
                    @click="confirmDiscard = true"
                />
                <v-spacer />
                <v-btn
                    color="primary"
                    type="submit"
                    :prepend-icon="timerActive ? 'mdi-stop' : 'mdi-play'"
                    :loading="form.processing"
                    :text="timerActive ? t('Stop') : t('Start')"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
<script lang="ts" setup>
import type { ConnectionExtended, TimeLog, VFormRef } from '@/types'

import { useTimeLogStore } from '@/stores/time-log'

import { emitter } from '~/resources/application/mitt'

defineOptions({ name: 'TimeLogForm' })

const { t } = useI18n()
const { isRequired } = useValidation()

const confirmDiscard = ref(false)

const props = withDefaults(
    defineProps<{
        defaults?: Partial<TimeLog>
        variant?: 'create' | 'edit' | 'timer'
    }>(),
    {
        defaults: undefined,
        variant: 'create',
    },
)

const emit = defineEmits<{
    (e: 'discard'): void
    (e: 'processing', val: boolean): void
    (e: 'start', project: number, task: number, comments?: string): void
}>()

// Dates
const today = new Date().toISOString()
const lastYear = new Date()
lastYear.setFullYear(new Date().getFullYear() - 1)

const { formatDuration } = useDate()

const duration = computed(() => {
    if (!form.fields.start_time || !form.fields.stop_time) return
    return formatDuration(form.fields.start_time, form.fields.stop_time)
})

const timerActive = computed(() => {
    return props.variant === 'timer' && !!props.defaults?.start_time
})

const timerErrors = computed(() => {
    return [
        form.errors.date,
        form.errors.start_time,
        form.errors.stop_time,
    ].filter((e): e is string => !!e)
})

// Column sizes
const sizes = computed(() => {
    return {
        cols: '12',
        lg: props.variant === 'timer' ? undefined : '3',
        md: undefined,
        sm: props.variant === 'timer' ? undefined : '6',
    }
})

// Connections
const fetchingConnections = ref(false)
const timeLogStore = useTimeLogStore()
const connections = ref<ConnectionExtended[]>([])
const fetchConnections = async () => {
    try {
        fetchingConnections.value = true
        connections.value = await timeLogStore.getConnections()
    } catch (e) {
        console.error(e)
    } finally {
        fetchingConnections.value = false
    }
}

// Projects of current user
const projects = computed(() => {
    return connections.value
        .map((c) => c.project)
        .filter((p, i, s) => s.findIndex((p2) => p2.id === p.id) === i)
})

// Tasks of selected project
const tasks = computed(() => {
    return connections.value
        .filter((ut) => ut.project.id === form.fields.project_id)
        .map((ut) => ut.task)
})

onMounted(() => {
    fetchConnections()
    resetFields()
})

// Reset fields to default values
const resetFields = () => {
    form.fields.project_id = props.defaults?.project_id
    form.fields.task_id = props.defaults?.task_id
    form.fields.date = props.defaults?.date ?? today
    form.fields.start_time = formatTime(props.defaults?.start_time)
    form.fields.stop_time = formatTime(props.defaults?.stop_time)
    form.fields.comments = props.defaults?.comments
}

const form = useForm<Partial<TimeLog>>({
    fields: {
        comments: undefined,
        date: undefined,
        project_id: undefined,
        start_time: undefined,
        stop_time: undefined,
        task_id: undefined,
    },
    method: props.variant === 'edit' ? 'PATCH' : 'POST',
    url: route(`time-logs.${props.variant === 'edit' ? 'update' : 'store'}`, {
        time_log: props.defaults?.id,
    }),
})

watch(
    () => form.processing,
    (val) => {
        emit('processing', val)
    },
)

const formRef = ref<null | VFormRef>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return

    // If timer has no start time yet, start the timer
    if (
        props.variant === 'timer' &&
        !props.defaults?.start_time &&
        form.fields.project_id &&
        form.fields.task_id
    ) {
        emit(
            'start',
            form.fields.project_id,
            form.fields.task_id,
            form.fields.comments,
        )
        resetFields()
    } else {
        // If timer, stop the timer
        if (props.variant === 'timer') {
            form.fields.stop_time = new Date().toLocaleTimeString('nl')
        }
        form.submit().then((res) => {
            // If submission was successful, reset the form
            if (
                !Object.keys(res.response?.data?.view?.properties?.errors ?? {})
                    .length
            ) {
                resetFields()
                emit('discard')
                emitter.emit('time-log:refresh', true)
            }
        })
    }
}
</script>
