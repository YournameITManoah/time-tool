<template layout="guest">
    <div>
        <v-card-title>Reset Password</v-card-title>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <field-email
                            v-model="form.fields.email"
                            :errors="form.errors.email"
                            readonly
                        />
                    </v-col>
                    <v-col cols="12">
                        <field-password
                            v-model="form.fields.password"
                            :errors="form.errors.password"
                            hint="new-password"
                        />
                    </v-col>
                    <v-col cols="12">
                        <field-password
                            v-model="form.fields.password_confirmation"
                            :errors="form.errors.password_confirmation"
                            hint="new-password"
                            label="Password Confirmation"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            :loading="form.processing"
                            block
                            color="primary"
                            type="submit"
                        >
                            Reset Password
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '~/resources/types'

useHead({ title: 'Reset Password' })

const props = defineProps<{
    email: string
    token: string
}>()

const form = useForm({
    url: route('password.store'),
    fields: {
        token: props.token,
        email: props.email,
        password: '',
        password_confirmation: '',
    },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
