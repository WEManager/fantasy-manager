import type { Paginate } from '~/interfaces/Paginate'
import type { Player } from '~/interfaces/Player'

import { Head, Link } from '@inertiajs/react'
import { Pagination, Table } from 'flowbite-react'

import Layout from '~/Layouts/Layout'

type PlayerIndexProps = {
  response: Paginate<Player>
}

export default function PlayerIndex({ response }: PlayerIndexProps) {
  console.log('🚀 ~ file: Index.tsx ~ line 14 ~ PlayerIndex ~ response', response)
  const { data: players } = response

  const handlePageChange = (pageNumber: number) => {
    router.get('/p', { page: pageNumber }, { replace: true })
  }

  return (
    <Layout>
      <Head title="Clubes" />

      <div className="container mx-auto">
        {!response.total ? (
          <p>vazio</p>
        ) : (
          <>
            <Table>
              <Table.Head>
                <Table.HeadCell>Nome</Table.HeadCell>
              </Table.Head>

              <Table.Body className="divide-y">
                {players.map((player) => (
                  <Table.Row
                    key={player.id}
                    className={`bg-white dark:border-gray-700 dark:bg-gray-800 `}
                  >
                    <Link href={route('player.show', player)}>{player.know_as}</Link>
                  </Table.Row>
                ))}
              </Table.Body>
            </Table>

            <div className="flex items-center justify-center text-center">
              <Pagination
                currentPage={response.current_page}
                totalPages={response.last_page}
                onPageChange={handlePageChange}
              />
            </div>
          </>
        )}
      </div>
    </Layout>
  )
}
