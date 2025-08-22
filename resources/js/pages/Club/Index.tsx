import type { Club } from '~/interfaces/Club'
import type { Paginate } from '~/interfaces/Paginate'

import { Head, Link } from '@inertiajs/react'
import { Pagination } from 'flowbite-react'
import { route } from 'ziggy-js'

import Layout from '~/Layouts/Layout'

type ClubIndexProps = {
  response: Paginate<Club>
}

export default function ClubIndex({ response }: ClubIndexProps) {
  const { data: clubs } = response

  const handlePageChange = (pageNumber: number) => {
    router.get('/c', { page: pageNumber }, { replace: true })
  }

  return (
    <Layout>
      <Head title="Clubes" />

      <div className="container mx-auto">
        <div className="grid grid-cols-5 gap-3 mt-5">
          {clubs.map((club) => (
            <Link
              href={route('club.show', club)}
              key={club.id}
              className="rounded border border-gray-200 p-4 flex flex-col items-center"
              style={{ backgroundColor: club.colors[0] }}
            >
              <h3 className="font-bold" style={{ color: club.colors[1] }}>
                {club.name}
              </h3>
            </Link>
          ))}
        </div>

        <div className="flex items-center justify-center text-center">
          <Pagination
            currentPage={response.current_page}
            totalPages={response.last_page}
            onPageChange={handlePageChange}
          />
        </div>
      </div>
    </Layout>
  )
}
