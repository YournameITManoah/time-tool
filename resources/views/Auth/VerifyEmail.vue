<template layout="guest">
    <div>
        <v-card-title>Email Verification</v-card-title>
        <v-form
            ref="formRef"
            :disabled="form.processing"
            @submit.prevent="submit"
        >
            <div class="text-subtitle-2 text-medium-emphasis mb-4">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>
            <v-alert v-if="verificationLinkSent" class="mb-4">
                A new verification link has been sent to the email address you
                provided during registration.
            </v-alert>
            <v-card-actions>
                <v-btn :loading="form.processing" color="primary" type="submit">
                    Resend Verification Email
                </v-btn>
                <v-spacer />
                <router-link :href="route('logout')" as="div" method="post">
                    <v-btn color="error">Log Out</v-btn>
                </router-link>
            </v-card-actions>
        </v-form>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '~/resources/types'

useHead({ title: 'Email Verification' })

const props = defineProps<{
    status?: string | null
}>()

const form = useForm({
    url: route('verification.send'),
    fields: [],
})

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
)

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
