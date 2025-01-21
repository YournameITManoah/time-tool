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
                                :loading="fetchingUserTasks"
                                :disabled="
                                    variant === 'timer' &&
                                    !!defaults?.start_time
                                "
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
                                :disabled="
                                    variant === 'timer' &&
                                    !!defaults?.start_time
                                "
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
                        </template>
                    </v-row>
                    <v-row v-if="variant !== 'timer'">
                        <v-spacer />
                        <router-link
                            :disabled="form.processing"
                            :href="route('time-log.index')"
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
                <v-spacer></v-spacer>
                <v-btn
                    color="error"
                    type="reset"
                    :disabled="form.processing"
                    :text="t('Cancel')"
                    @click="emit('cancel')"
                />
                <v-btn
                    color="primary"
                    type="submit"
                    :loading="form.processing"
                    :text="defaults?.start_time ? t('Save') : t('Start')"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
<script lang="ts" setup>
import type { TimeLog, UserTaskExtended, VFormRef } from '@/types'

import { useTimeLogStore } from '@/stores/time-log'

import { emitter } from '~/resources/application/mitt'

defineOptions({ name: 'TimeLogForm' })

const { t } = useI18n()
const { isRequired } = useValidation()

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
    (e: 'cancel'): void
    (e: 'start', project: number, task: number): void
}>()

// Dates
const today = new Date().toISOString()
const lastYear = new Date()
lastYear.setFullYear(new Date().getFullYear() - 1)

// Column sizes
const sizes = computed(() => {
    return {
        cols: '12',
        lg: props.variant === 'timer' ? undefined : '3',
        md: undefined,
        sm: props.variant === 'timer' ? undefined : '6',
    }
})

// User tasks
const fetchingUserTasks = ref(false)
const timeLogStore = useTimeLogStore()
const userTasks = ref<UserTaskExtended[]>([])
const fetchUserTasks = async () => {
    try {
        fetchingUserTasks.value = true
        userTasks.value = await timeLogStore.getUserTasks()
    } catch (e) {
        console.error(e)
    } finally {
        fetchingUserTasks.value = false
    }
}

// Projects of current user
const projects = computed(() => {
    return userTasks.value
        .map((ut) => ut.project)
        .filter((p, i, s) => s.findIndex((p2) => p2.id === p.id) === i)
        .filter(
            (p) =>
                (!p.start_date || p.start_date < today) &&
                (!p.end_date || p.end_date > today),
        )
})

// Tasks of selected project
const tasks = computed(() => {
    return userTasks.value
        .filter((ut) => ut.project.id === form.fields.project_id)
        .map((ut) => ut.task)
})

onMounted(() => {
    fetchUserTasks()
    resetFields()
})

// Reset fields to default values
const resetFields = () => {
    form.fields.project_id = props.defaults?.project_id
    form.fields.task_id = props.defaults?.task_id
    form.fields.date = props.defaults?.date
    form.fields.start_time = formatTime(props.defaults?.start_time)
    form.fields.stop_time = formatTime(props.defaults?.stop_time)
}

const form = useForm<Partial<TimeLog>>({
    fields: {
        date: undefined,
        project_id: undefined,
        start_time: undefined,
        stop_time: undefined,
        task_id: undefined,
    },
    method: props.variant === 'edit' ? 'PATCH' : 'POST',
    url: route(`time-log.${props.variant === 'edit' ? 'update' : 'store'}`, {
        time_log: props.defaults?.id,
    }),
})

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
        emit('start', form.fields.project_id, form.fields.task_id)
        resetFields()
    } else {
        // If timer, stop the timer
        if (props.variant === 'timer') {
            form.fields.stop_time = new Date().toLocaleTimeString('nl')
            emit('cancel')
        }
        form.submit().then((res) => {
            // If submission was successful, reset the form
            if (
                !Object.keys(res.response?.data?.view?.properties?.errors ?? {})
                    .length
            ) {
                resetFields()
                emitter.emit('time-log:refresh', true)
            }
        })
    }
}
</script>
