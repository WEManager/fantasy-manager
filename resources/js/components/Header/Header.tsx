import { Link } from '@inertiajs/inertia-react'

import { MainNav } from './MainNav'
import { MobileNav } from './MobileMenu'
import { ThemeToggle } from './ThemeToggle'
import { buttonVariants } from '../ui/Button'
import { Icons } from '../Icons'

export function Header() {
  return (
    <header className="sticky top-0 z-40 w-full border-b border-b-slate-200 bg-white dark:border-b-slate-700 dark:bg-slate-900">
      <div className="container flex h-16 items-center">
        <MainNav />

        <MobileNav />

        <div className="flex flex-1 items-center justify-end space-x-4">
          <div className="hidden flex-1 sm:grow-0 md:flex">{/* <DocsSearch /> */}</div>

          <nav className="flex items-center space-x-1">
            <Link href="#" target="_blank" rel="noreferrer">
              <div
                className={buttonVariants({
                  size: 'sm',
                  variant: 'ghost',
                  className: 'text-slate-700 dark:text-slate-400',
                })}>
                <Icons.gitHub className="h-5 w-5" />
                <span className="sr-only">GitHub</span>
              </div>
            </Link>

            <Link href="#" target="_blank" rel="noreferrer">
              <div
                className={buttonVariants({
                  size: 'sm',
                  variant: 'ghost',
                  className: 'text-slate-700 dark:text-slate-400',
                })}>
                <Icons.twitter className="h-5 w-5 fill-current" />
                <span className="sr-only">Twitter</span>
              </div>
            </Link>

            <ThemeToggle />
          </nav>
        </div>
      </div>
    </header>
  )
}
