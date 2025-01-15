<template layout="guest">
    <div>
        <v-card-title>{{ t('Confirm Password') }}</v-card-title>
        <v-form ref="formRef" @submit.prevent="submit">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="text-subtitle-2 text-medium-emphasis">
                            {{ t('messages.confirm_password_text') }}
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
                            :text="t('Confirm')"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>
<script lang="ts" setup>
import type { VFormRef } from '@/types'

const { t } = useI18n()

useHead({ title: t('Confirm Password') })

defineProps<{
    status?: null | string
}>()

const form = useForm({
    fields: {
        password: '',
    },
})

const formRef = ref<null | VFormRef>(null)
const submit = async () => {
    const result = await formRef.value?.validate()
    if (!result?.valid) return
    form.submit()
}
</script>
