<script setup lang="ts">
import { Quote, Star } from 'lucide-vue-next'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t, tm, rt } = useI18n()

interface Testimonial {
  quote: string
  name: string
  role: string
}

const items = computed<Testimonial[]>(() => {
  const raw = tm('home.testimonials.items') as Testimonial[]
  return raw.map((item) => ({
    quote: rt(item.quote),
    name: rt(item.name),
    role: rt(item.role),
  }))
})

const accents = ['from-brand-500 to-fuchsia-500', 'from-amber-500 to-orange-500', 'from-emerald-500 to-teal-500']
</script>

<template>
  <section class="bg-slate-900/30 py-24">
    <div class="mx-auto max-w-7xl px-6">
      <div class="mx-auto max-w-2xl text-center">
        <span class="kicker">{{ t('home.testimonials.kicker') }}</span>
        <h2 class="mt-4 section-title">{{ t('home.testimonials.title') }}</h2>
        <p class="section-subtitle mx-auto">{{ t('home.testimonials.subtitle') }}</p>
      </div>

      <div class="mt-16 grid gap-6 md:grid-cols-3">
        <figure
          v-for="(item, idx) in items"
          :key="idx"
          class="card card-hover relative flex flex-col"
        >
          <Quote class="h-8 w-8 text-brand-400/40" />
          <blockquote class="mt-3 flex-1 text-sm leading-relaxed text-slate-300">
            &ldquo;{{ item.quote }}&rdquo;
          </blockquote>
          <div class="mt-1 flex items-center gap-1 text-amber-400">
            <Star v-for="i in 5" :key="i" class="h-3.5 w-3.5 fill-current" />
          </div>
          <figcaption class="mt-5 flex items-center gap-3 border-t border-slate-800 pt-5">
            <div
              class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br text-sm font-bold text-white"
              :class="accents[idx % accents.length]"
            >
              {{ item.name.split(' ').map((p) => p[0]).join('').slice(0, 2) }}
            </div>
            <div>
              <div class="text-sm font-semibold text-white">{{ item.name }}</div>
              <div class="text-xs text-slate-400">{{ item.role }}</div>
            </div>
          </figcaption>
        </figure>
      </div>
    </div>
  </section>
</template>
