export interface User {
  id: number
  name: string
  email: string
  avatar_url: string | null
  locale: 'en' | 'az' | 'ru'
  email_verified_at: string | null
  created_at: string
}

export type CvStatus = 'draft' | 'published' | 'archived'

export interface Template {
  id: number
  name: string
  slug: string
  description: string | null
  category: string
  preview_url: string | null
  price: {
    minor: number
    major: number
    currency: string
    formatted: string
  }
}

export interface Cv {
  id: string
  title: string
  slug: string
  status: CvStatus
  is_public: boolean
  content: Record<string, unknown>
  template: Template | null
  published_at: string | null
  created_at: string
  updated_at: string
}

export interface ApiResource<T> {
  data: T
}

export interface ApiCollection<T> {
  data: T[]
  links?: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
  meta?: {
    current_page: number
    from: number
    last_page: number
    per_page: number
    to: number
    total: number
  }
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}
