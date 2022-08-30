import { Head, Link } from '@inertiajs/inertia-react'
import { Button } from 'flowbite-react'
import route from 'ziggy-js'
import Layout from '@/Layouts/Layout'
import { Paginate } from '@/interfaces/Paginate'
import { Club } from '@/interfaces/Club'

type ClubIndexProps = {
  response: Paginate<Club>
}

export default function ClubIndex({ response }: ClubIndexProps) {
  const { data: clubs, links } = response

  return (
    <Layout>
      <Head title="Clubes" />

      {clubs.map((club) => (
        <div key={club.id}>
          <Link href={route('club.show', club)}>{club.name}</Link>
        </div>
      ))}

      <div className="flex gap-2 mt-4">
        {links.map((link) => (
          <Link href={link.url} key={link.label}>
            <Button>{link.label}</Button>
          </Link>
        ))}
      </div>
    </Layout>
  )
}
