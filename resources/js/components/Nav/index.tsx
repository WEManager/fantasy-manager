import { usePage, Link } from '@inertiajs/inertia-react'
import { Avatar, Button, Navbar, Dropdown } from 'flowbite-react'
import route from 'ziggy-js'

const Nav = () => {
  const {
    props: { auth },
  } = usePage()

  return (
    <Navbar>
      <Navbar.Brand>
        <img src="https://flowbite.com/docs/images/logo.svg" className="mr-3 h-6 sm:h-9" alt="Flowbite Logo" />
        <span className="self-center whitespace-nowrap text-xl font-semibold dark:text-white">WEManager</span>
      </Navbar.Brand>

      <div className="flex md:order-2">
        {!auth?.user ? (
          <Link href={route('login')}>
            <Button>Entrar</Button>
          </Link>
        ) : (
          <Dropdown
            arrowIcon={false}
            inline={true}
            label={
              <Avatar
                alt="User settings"
                img="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                rounded={true}
              />
            }>
            <Dropdown.Header>
              <span className="block text-sm">{auth.user.name}</span>

              <span className="block truncate text-sm font-medium">{auth.user.email}</span>
            </Dropdown.Header>

            <Dropdown.Item>Dashboard</Dropdown.Item>

            <Dropdown.Item>Settings</Dropdown.Item>

            <Dropdown.Item>Earnings</Dropdown.Item>

            <Dropdown.Divider />

            <Dropdown.Item>
              <Link href={route('logout')} method="post" as="button">
                Sair
              </Link>
            </Dropdown.Item>
          </Dropdown>
        )}

        <Navbar.Toggle />
      </div>

      <Navbar.Collapse>
        <Navbar.Link href="/navbars" active={true}>
          Home
        </Navbar.Link>

        <Navbar.Link href="/navbars">About</Navbar.Link>

        <Navbar.Link href="/navbars">Services</Navbar.Link>

        <Navbar.Link href="/navbars">Pricing</Navbar.Link>

        <Navbar.Link href="/navbars">Contact</Navbar.Link>
      </Navbar.Collapse>
    </Navbar>
  )
}

export default Nav
