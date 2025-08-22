import {
  createContext,
  type Dispatch,
  type ReactNode,
  type SetStateAction,
  useContext,
  useEffect,
  useState,
} from 'react'

type Theme = 'dark' | 'light'

type ThemeContextInterface = {
  theme: Theme
  setTheme: Dispatch<SetStateAction<Theme>>
}

type ThemeProviderProps = {
  children: ReactNode
}

const ThemeContext = createContext<ThemeContextInterface | null>(null)

function getInitialTheme(): Theme {
  if (typeof window !== 'undefined' && window.localStorage) {
    const storedPrefs = window.localStorage.getItem('color-theme')

    if (typeof storedPrefs === 'string') {
      return storedPrefs as Theme
    }

    const datasetTheme = window.document.documentElement.dataset.theme

    if (datasetTheme) {
      return datasetTheme as Theme
    }
  }

  return 'dark'
}

export function ThemeProvider({ children }: ThemeProviderProps) {
  const [theme, setTheme] = useState<Theme>(getInitialTheme())

  function setThemeOnPage() {
    const root = window.document.documentElement

    const isDark = theme === 'dark'

    root.dataset.theme = isDark ? 'dark' : 'light'

    root.style.colorScheme = isDark ? 'dark' : 'light'

    localStorage.setItem('color-theme', theme)
  }

  useEffect(() => {
    setThemeOnPage()
  }, [setThemeOnPage])

  return (
    <ThemeContext.Provider
      value={{
        theme,
        setTheme,
      }}
    >
      {children}
    </ThemeContext.Provider>
  )
}

export function useThemeContext() {
  const context = useContext(ThemeContext)

  if (!context) {
    throw new Error('useThemeContext must be used within a ThemeProvider')
  }

  return context
}
