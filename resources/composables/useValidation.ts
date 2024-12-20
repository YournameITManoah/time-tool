export function useValidation() {
    const { t } = useI18n()

    const isRequired = (v: string | number | boolean) =>
        (v !== undefined && v !== null && v !== '') || t('validation.required')

    return { isRequired }
}
