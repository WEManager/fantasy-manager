import { createContext, type PropsWithChildren, useContext, useEffect, useRef } from 'react'
import { createStore, type StoreApi, useStore } from 'zustand'

export enum Theme {
  Light = 'light',
  Dark = 'dark',
  System = 'system',
}

interface IThemeContext {
  currentTheme: Theme
  setCurrentTheme: (theme: Theme) => void
}

interface ThemeProviderProps extends PropsWithChildren {
  defaultTheme?: Theme
  storageKey?: string
}

const ThemeContext = createContext<StoreApi<IThemeContext>>({} as StoreApi<IThemeContext>)

const prefersDark = () =>
  typeof window !== 'undefined' && window.matchMedia('(prefers-color-scheme: dark)').matches

const applyTheme = (theme: Theme) => {
  const isDark = theme === Theme.Dark || (theme === Theme.System && prefersDark())
  // usa classList.toggle para trabalhar com Tailwind/DaisyUI/etc
  document.documentElement.classList.toggle('dark', isDark)
}

export function ThemeProvider({
  children,
  defaultTheme = Theme.Dark,
  storageKey = 'color-theme',
}: PropsWithChildren<ThemeProviderProps>) {
  const storeRef = useRef<StoreApi<IThemeContext>>(null)

  if (!storeRef.current) {
    storeRef.current = createStore<IThemeContext>((set) => ({
      currentTheme: defaultTheme,
      setCurrentTheme: (theme) => set({ currentTheme: theme }),
    }))
  }

  useEffect(() => {
    if (typeof window === 'undefined' || !storeRef.current) return

    const store = storeRef.current
    const mq = window.matchMedia('(prefers-color-scheme: dark)')

    const saved = localStorage.getItem(storageKey) as Theme | null
    if (saved) store.setState({ currentTheme: saved })

    applyTheme(store.getState().currentTheme)

    const unsub = store.subscribe((state, prev) => {
      if (state.currentTheme !== prev.currentTheme) {
        localStorage.setItem(storageKey, state.currentTheme)
        applyTheme(state.currentTheme)
      }
    })

    const onSystemChange = () => {
      if (store.getState().currentTheme === Theme.System) {
        applyTheme(Theme.System)
      }
    }
    mq.addEventListener?.('change', onSystemChange)
    // old browser support
    mq.addListener?.(onSystemChange)

    return () => {
      unsub()
      mq.removeEventListener?.('change', onSystemChange)
      // old browser support
      mq.removeListener?.(onSystemChange)
    }
  }, [storageKey])

  return <ThemeContext.Provider value={storeRef.current}>{children}</ThemeContext.Provider>
}

function useThemeStore<T>(selector: (state: IThemeContext) => T): T {
  const store = useContext(ThemeContext)

  if (!store) {
    throw new Error('useTheme must be used within a ThemeProvider')
  }

  return useStore(store, selector)
}

// Hooks
export function useTheme(): Theme {
  return useThemeStore((s) => s.currentTheme)
}

export function useSetTheme(): (theme: Theme) => void {
  return useThemeStore((s) => s.setCurrentTheme)
}
