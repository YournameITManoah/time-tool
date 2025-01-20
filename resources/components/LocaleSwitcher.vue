<template>
    <v-form
        v-if="currentLocale && localeList.length > 1"
        ref="formRef"
        :disabled="form.processing"
        @submit.prevent
    >
        <v-menu>
            <template #activator="{ props: menuProps }">
                <v-btn
                    v-if="variant === 'button'"
                    variant="text"
                    :disabled="form.processing"
                    :loading="form.processing"
                    v-bind="menuProps"
                >
                    <v-img
                        :src="`/img/flags/${currentLocale.value}.svg`"
                        :alt="currentLocale.title"
                        width="32px"
                    />
                    <v-tooltip
                        activator="parent"
                        :text="currentLocale.title"
                        location="top"
                    />
                </v-btn>
                <v-list-item
                    v-else
                    :prepend-avatar="`/img/flags/${currentLocale.value}.svg`"
                    :title="currentLocale.title"
                    :disabled="form.processing"
                    v-bind="menuProps"
                />
            </template>
            <v-list>
                <template v-for="item in localeList" :key="item.value">
                    <v-list-item
                        v-if="currentLocale.value !== item.value"
                        :title="item.title"
                        @click="submit(item.value)"
                    >
                        <template #prepend>
                            <v-avatar size="small" aria-hidden="true">
                                <v-img
                                    :src="`/img/flags/${item.value}.svg`"
                                    :alt="item.title"
                                />
                            </v-avatar>
                        </template>
                    </v-list-item>
                </template>
            </v-list>
        </v-menu>
    </v-form>
</template>
<script setup lang="ts">
import type { SupportedLocale } from '@/application/i18n'
import type { VFormRef } from '@/types'

const { locale } = useI18n()
const locales = useProperty<Record<SupportedLocale, string>>('locales')

defineProps<{
    variant: 'button' | 'list'
}>()

// List of locales
const localeList = computed(() => {
    return (Object.keys(locales.value) as SupportedLocale[]).map((value) => ({
        title: locales.value[value],
        value,
    }))
})

const currentLocale = useArrayFind(localeList, (l) => l.value === locale.value)

const form = useForm<{ locale: SupportedLocale }>({
    fields: { locale: 'en' },
    method: 'PUT',
    url: route('locale.update'),
})

const formRef = ref<null | VFormRef>(null)
const submit = async (newLocale: SupportedLocale = 'en') => {
    form.fields.locale = newLocale
    form.submit()
}
</script>
