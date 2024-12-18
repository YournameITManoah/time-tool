<template layout="guest">
    <div>
        <v-card-title>Confirm Password</v-card-title>
        <v-form ref="formRef" @submit.prevent="submit">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="text-subtitle-2 text-medium-emphasis">
                            This is a secure area of the application. Please
                            confirm your password before continuing.
                        </div>
                    </v-col>
                    <v-col v-if="status" cols="12">
                        <v-alert :text="status" />
                    </v-col>
                    <v-col cols="12">
                        <field-password
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="current-password"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                        >
                            Confirm
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '~/resources/types'

useHead({ title: 'Confirm Password' })

defineProps<{
    status?: string | null
}>()

const form = useForm({
    fields: {
        password: '',
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
