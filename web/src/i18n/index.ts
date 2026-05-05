import { createI18n } from 'vue-i18n'

import az from './locales/az.json'
import en from './locales/en.json'
import ru from './locales/ru.json'

export const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('locale') ?? 'en',
  fallbackLocale: 'en',
  messages: { en, az, ru },
})
