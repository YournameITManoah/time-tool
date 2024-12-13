<script lang="ts" setup>
import {
    VFormRef,
    PagedResult,
    TimeLog,
    UserTaskExtended,
} from '~/resources/types'

const props = defineProps<{
    edit?: TimeLog
}>()

const today = new Date().toISOString()
const lastYear = new Date()
lastYear.setFullYear(new Date().getFullYear() - 1)

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
        title: props.edit ? 'Edit' : 'Create',
        href: route(`time-log.${props.edit ? 'edit' : 'create'}`, {
            time_log: props.edit?.id,
        }),
        disabled: true,
    },
]

const fetchingUserTasks = ref(false)
const userTasks = ref<UserTaskExtended[]>([])
const fetchUserTasks = async () => {
    try {
        fetchingUserTasks.value = true
        const result = await window.axios<PagedResult<UserTaskExtended>>(
            '/api/user-task/my',
            { params: { limit: -1 } },
        )

        userTasks.value = result.data.data
    } catch (e) {
        console.error(e)
    } finally {
        fetchingUserTasks.value = false
    }
}

const projects = computed(() => {
    return [...new Set(userTasks.value.map((ut) => ut.project))]
})

const tasks = computed(() => {
    return userTasks.value
        .filter((ut) => ut.project.id === form.fields.project_id)
        .map((ut) => ut.task)
})

onMounted(() => {
    fetchUserTasks()
})

const form = useForm({
    method: props.edit ? 'PATCH' : 'POST',
    url: route(`time-log.${props.edit ? 'update' : 'store'}`, {
        time_log: props.edit?.id,
    }),
    fields: {
        project_id: props.edit?.project_id,
        task_id: props.edit?.task_id,
        date: props.edit?.date,
        start_time: props.edit?.start_time?.replace(
            /^(\d{2}:\d{2})(:\d{2})?$/,
            '$1',
        ),
        stop_time: props.edit?.stop_time?.replace(
            /^(\d{2}:\d{2})(:\d{2})?$/,
            '$1',
        ),
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit().then(() => {
        form.fields = {
            project_id: props.edit?.project_id,
            task_id: props.edit?.task_id,
            date: props.edit?.date,
            start_time: props.edit?.start_time?.replace(
                /^(\d{2}:\d{2})(:\d{2})?$/,
                '$1',
            ),
            stop_time: props.edit?.stop_time?.replace(
                /^(\d{2}:\d{2})(:\d{2})?$/,
                '$1',
            ),
        }
    })
}
</script>

<template>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">
                {{ edit ? 'Edit' : 'Create' }} Time Log
            </h5>
            <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
        </div>
        <v-card>
            <v-card-text>
                <v-form
                    ref="formRef"
                    :disabled="form.processing"
                    @submit.prevent="submit"
                >
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6" lg="3">
                                <v-select
                                    v-model="form.fields.project_id"
                                    label="Project"
                                    :items="projects"
                                    :error-messages="form.errors.project_id"
                                    :loading="fetchingUserTasks"
                                    prepend-icon="mdi-cards-variant"
                                    item-title="name"
                                    item-value="id"
                                    :rules="[isRequired]"
                                    required
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <v-select
                                    v-model="form.fields.task_id"
                                    label="Task"
                                    :items="tasks"
                                    :error-messages="form.errors.task_id"
                                    prepend-icon="mdi-cards-variant"
                                    item-title="name"
                                    item-value="id"
                                    :rules="[isRequired]"
                                    required
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <date-field
                                    v-model="form.fields.date"
                                    label="Date"
                                    :min="lastYear.toISOString()"
                                    :max="today"
                                    :rules="[isRequired]"
                                    :disabled="form.processing"
                                    :error-messages="form.errors.date"
                                    required
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <TimeField
                                    v-model="form.fields.start_time"
                                    label="Start Time"
                                    :max="form.fields.stop_time"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.start_time"
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <TimeField
                                    v-model="form.fields.stop_time"
                                    label="Stop Time"
                                    :min="form.fields.start_time"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.stop_time"
                                />
                            </v-col>
                            <v-col cols="12">
                                <v-btn
                                    :loading="form.processing"
                                    color="primary"
                                    type="submit"
                                    class="mr-4"
                                >
                                    {{ edit ? 'Edit' : 'Create' }}
                                </v-btn>
                                <router-link
                                    :disabled="form.processing"
                                    :href="route('time-log.index')"
                                >
                                    <v-btn
                                        :disabled="form.processing"
                                        color="error"
                                        tabindex="-1"
                                    >
                                        Cancel
                                    </v-btn>
                                </router-link>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>
