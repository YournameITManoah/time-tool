<script lang="ts" setup>
import { VFormRef, PagedResult, ProjectWithClient } from '~/resources/types'

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

const fetchingProjects = ref(false)
const projects = ref<ProjectWithClient[]>([])
const fetchProjects = async () => {
    try {
        fetchingProjects.value = true
        const result = await window.axios<PagedResult<ProjectWithClient>>(
            '/api/project/my',
            { params: { limit: -1 } },
        )

        projects.value = result.data.data
    } catch (e) {
        console.error(e)
    } finally {
        fetchingProjects.value = false
    }
}

onMounted(() => {
    fetchProjects()
})

const form = useForm({
    url: route('time-log.store'),
    fields: {
        project_id: null,
        date: null,
        start_time: null,
        stop_time: null,
        description: null,
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
                                    :loading="fetchingProjects"
                                    prepend-icon="mdi-cards-variant"
                                    item-title="name"
                                    item-value="id"
                                    :rules="[isRequired]"
                                    required
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <v-date-input
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
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.start_time"
                                    required
                                />
                            </v-col>
                            <v-col cols="12" sm="6" lg="3">
                                <TimeField
                                    v-model="form.fields.stop_time"
                                    label="Stop Time"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.stop_time"
                                    required
                                />
                            </v-col>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="form.fields.description"
                                    label="Description"
                                    :rules="[isRequired]"
                                    :error-messages="form.errors.description"
                                    required
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
