<template>
    <v-form
        v-if="otherLocale"
        ref="formRef"
        :disabled="form.processing"
        @submit.prevent="submit"
    >
        <v-btn
            v-if="button"
            variant="text"
            type="submit"
            :disabled="form.processing"
            :loading="form.processing"
        >
            <v-img :src="`/img/flags/${otherLocale.value}.svg`" width="32px" />
        </v-btn>
        <v-list-item
            v-else
            :prepend-avatar="`/img/flags/${otherLocale.value}.svg`"
            :title="otherLocale.title"
            :disabled="form.processing"
            @click="submit"
        />
    </v-form>
</template>
<script setup lang="ts">
import type { SupportedLocale } from '@/application/i18n'
import type { VFormRef } from '@/types'

const { locale } = useI18n()

defineProps<{
    button?: boolean
}>()

const locales: { title: string; value: SupportedLocale }[] = [
    { title: 'English', value: 'en' },
    { title: 'Nederlands', value: 'nl' },
]

const otherLocale = useArrayFind(locales, (l) => l.value !== locale.value)

const form = useForm<{ locale: SupportedLocale }>({
    method: 'PUT',
    url: route('locale.update'),
    fields: { locale: 'en' },
})

const formRef = ref<VFormRef | null>(null)
const submit = async () => {
    form.fields.locale = otherLocale.value?.value ?? 'en'
    form.submit()
}
</script>
