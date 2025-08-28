import { useId } from 'react'
import { Link } from '@inertiajs/react'
import { Menu } from 'lucide-react'

import { Button } from '~/modules/core/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '~/modules/core/components/ui/dropdown-menu'
import { Sheet, SheetContent, SheetTrigger } from '~/modules/core/components/ui/sheet'
import { useAuth } from '~/modules/core/hooks/useAuth'
import { cn } from '~/modules/core/utils/class-value'
import { ThemeToggle } from '~/modules/layouts/app/components/theme-toggle'

type NavbarProps = {
  className?: string
}

export function Navbar({ className }: NavbarProps) {
  const { user, isAuthenticated, hasClub } = useAuth()

  const club = user?.club
  const appName = import.meta.env.VITE_APP_NAME || 'Laravel'
  const navbarId = useId()

  return (
    <nav
      className={cn(
        'navbar navbar-expand-md navbar-light navbar-laravel border-b bg-background',
        className,
      )}
    >
      <div className="container mx-auto flex h-16 items-center justify-between px-4">
        {/* Brand */}
        <Link href={route('home')} className="navbar-brand font-semibold" aria-label={appName}>
          {appName}
        </Link>

        {/* Mobile menu button */}
        <Button
          size="icon"
          variant="ghost"
          className="navbar-toggler md:hidden"
          data-toggle="collapse"
          data-target={`#${navbarId}`}
          aria-controls={navbarId}
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <Menu className="h-5 w-5" />
          <span className="sr-only">Toggle navigation</span>
        </Button>

        {/* Desktop Navigation */}
        <div className="hidden navbar-collapse md:flex" id={navbarId}>
          {/* Right Side Of Navbar */}
          <ul className="navbar-nav ml-auto flex items-center gap-2">
            <ThemeToggle />

            {/* Authentication Links */}
            {!isAuthenticated ? (
              <>
                <li className="nav-item">
                  <Link href={route('login')} className="nav-link text-sm px-3 py-2">
                    Login
                  </Link>
                </li>
                <li className="nav-item">
                  <Link href={route('register')} className="nav-link text-sm px-3 py-2">
                    Register
                  </Link>
                </li>
              </>
            ) : (
              <>
                <li className="nav-item">
                  <a
                    href="https://discord.gg/tzFVp2y"
                    target="_blank"
                    rel="noreferrer"
                    className="nav-link text-sm px-3 py-2"
                  >
                    Join us on Discord
                  </a>
                </li>

                {/* Club dropdown (se existir) */}
                {hasClub && club && (
                  <li className="nav-item dropdown">
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="nav-link dropdown-toggle text-sm">
                          {club.name} <span className="caret">▼</span>
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent
                        align="end"
                        className="dropdown-menu dropdown-menu-right w-56"
                      >
                        <DropdownMenuItem asChild>
                          <Link
                            href={route('club.show', { club })}
                            className={cn(
                              'dropdown-item w-full',
                              route().current('club.show') ? 'active' : '',
                            )}
                          >
                            Clubhouse
                          </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem asChild>
                          <Link
                            href={route('show_club_players', { club })}
                            className={cn(
                              'dropdown-item w-full',
                              route().current('show_club_players') ? 'active' : '',
                            )}
                          >
                            Players
                          </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem asChild>
                          <Link
                            href={route('club.resign', { club })}
                            className="dropdown-item w-full"
                          >
                            Quit Job
                          </Link>
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </li>
                )}

                {/* User dropdown */}
                <li className="nav-item dropdown">
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="outline" className="nav-link dropdown-toggle text-sm">
                        {user?.name ?? 'Account'} <span className="caret">▼</span>
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                      align="end"
                      className="dropdown-menu dropdown-menu-right w-56"
                    >
                      <DropdownMenuItem asChild>
                        <Button variant="ghost" className="dropdown-item px-0" asChild>
                          <Link href={route('logout')} method="post" as="button">
                            Logout
                          </Link>
                        </Button>
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </li>
              </>
            )}
          </ul>
        </div>

        {/* Mobile menu */}
        <Sheet>
          <SheetTrigger asChild>
            <Button size="icon" variant="ghost" className="md:hidden">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Open menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="right" className="w-80">
            <div className="flex flex-col gap-2">
              <Link href={route('home')} className="font-semibold text-base">
                {appName}
              </Link>

              <div className="flex items-center justify-between">
                <span className="text-sm text-muted-foreground">Theme</span>
                <ThemeToggle />
              </div>

              {!isAuthenticated ? (
                <>
                  <Link href={route('login')} className="py-2">
                    Login
                  </Link>
                  <Link href={route('register')} className="py-2">
                    Register
                  </Link>
                </>
              ) : (
                <>
                  <a
                    href="https://discord.gg/tzFVp2y"
                    target="_blank"
                    rel="noreferrer"
                    className="py-2"
                  >
                    Join us on Discord
                  </a>

                  {hasClub && club && (
                    <>
                      <div className="text-xs uppercase text-muted-foreground">Club</div>
                      <Link href={route('club.show', { club })} className="py-2">
                        Clubhouse
                      </Link>
                      <Link href={route('show_club_players', { club })} className="py-2">
                        Players
                      </Link>
                      <Link href={route('club.resign', { club })} className="py-2">
                        Quit Job
                      </Link>
                    </>
                  )}

                  <div className="text-xs uppercase text-muted-foreground">Account</div>
                  <Button variant="ghost" className="px-0" asChild>
                    <Link href={route('logout')} method="post" as="button">
                      Logout
                    </Link>
                  </Button>
                </>
              )}
            </div>
          </SheetContent>
        </Sheet>
      </div>
    </nav>
  )
}
