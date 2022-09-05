import { Head, Link } from '@inertiajs/inertia-react'
import { Pagination, Table } from 'flowbite-react'
import route from 'ziggy-js'
import { Inertia } from '@inertiajs/inertia'
import { Paginate } from '@/interfaces/Paginate'
import { Player } from '@/interfaces/Player'
import Layout from '@/Layouts/Layout'

type PlayerIndexProps = {
  response: Paginate<Player>
}

export default function PlayerIndex({ response }: PlayerIndexProps) {
  console.log('🚀 ~ file: Index.tsx ~ line 14 ~ PlayerIndex ~ response', response)
  const { data: players } = response

  const handlePageChange = (pageNumber: number) => {
    Inertia.get('/p', { page: pageNumber }, { replace: true })
  }

  return (
    <Layout>
      <Head title="Jogadores" />

      <div className="container mx-auto">
        <div className="mt-5">
          {!response.total ? (
            <p>vazio</p>
          ) : (
            <>
              <Table>
                <Table.Head>
                  <Table.HeadCell>Nome</Table.HeadCell>
                  <Table.HeadCell>Nacionalidade</Table.HeadCell>
                  <Table.HeadCell>Clube</Table.HeadCell>
                </Table.Head>

                <Table.Body className="divide-y">
                  {players.map((player) => (
                    <Table.Row key={player.id} className={`bg-white dark:border-gray-700 dark:bg-gray-800 `}>
                      <Table.Cell className="whitespace-nowrap font-medium text-gray-900 dark:text-white">
                        <Link href={route('player.show', player)}>
                          <div className="flex items-center gap-2">
                            <img
                              src={player.image_url.replace('22_60', '22_240')}
                              alt={player.know_as}
                              className="w-14"
                            />
                            {player.know_as}
                          </div>
                        </Link>
                      </Table.Cell>

                      <Table.Cell>{player?.nation.name}</Table.Cell>

                      <Table.Cell>
                        {player?.club ? (
                          <Link href={route('club.show', player.club)}>{player?.club.name}</Link>
                        ) : (
                          'Free'
                        )}
                      </Table.Cell>
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
      </div>
    </Layout>
  )
}
