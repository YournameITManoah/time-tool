<script lang="ts" setup>
import { VFormRef, PagedResult, UserTaskExtended } from '~/resources/types'

defineOptions({ name: 'TimeLogCreate' })

useHead({ title: 'Create Time Log' })

const today = new Date().toISOString()

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
        title: 'Create',
        href: route('time-log.create'),
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
    url: route('time-log.store'),
    fields: {
        project_id: null,
        task_id: null,
        date: null,
        start_time: null,
        stop_time: null,
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>

<template layout>
    <div>
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">Create Time Log</h5>
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
                                    Create
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
