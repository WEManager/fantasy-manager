import { Head } from '@inertiajs/inertia-react'
import { Table } from 'flowbite-react'
import { Tournament } from '@/interfaces/Tournament'
import Layout from '@/Layouts/Layout'

type TournamentShowProps = {
  tournament: Tournament
}

export default function TournamentShow({ tournament }: TournamentShowProps) {
  return (
    <Layout>
      <Head title={tournament.name} />

      <section className="container mx-auto">
        <div>
          {tournament?.groups?.map((group) => (
            <Table key={group.id}>
              <Table.Head>
                <Table.HeadCell>Pos.</Table.HeadCell>
                <Table.HeadCell>Clube</Table.HeadCell>
                <Table.HeadCell>Jogos</Table.HeadCell>
                <Table.HeadCell>Vit.</Table.HeadCell>
                <Table.HeadCell>Emp.</Table.HeadCell>
                <Table.HeadCell>Der.</Table.HeadCell>
                <Table.HeadCell>GP</Table.HeadCell>
                <Table.HeadCell>GC</Table.HeadCell>
                <Table.HeadCell>SG</Table.HeadCell>
                <Table.HeadCell>Pontos</Table.HeadCell>
              </Table.Head>

              <Table.Body className="divide-y">
                {group.standings.map((standing, index) => (
                  <Table.Row
                    key={standing.club_id}
                    className={`bg-white dark:border-gray-700 dark:bg-gray-800 ${
                      tournament?.qualifications?.[index].status !== 'ended'
                        ? tournament?.qualifications?.[index].status === 'relegated'
                          ? 'dark:bg-red-500'
                          : 'dark:bg-yellow-500'
                        : ''
                    }`}>
                    <Table.Cell>{++index}</Table.Cell>

                    <Table.Cell className="whitespace-nowrap font-medium text-gray-900 dark:text-white">
                      {standing.club.name}
                    </Table.Cell>

                    <Table.Cell>{standing.won + standing.tie + standing.lost}</Table.Cell>

                    <Table.Cell>{standing.won}</Table.Cell>

                    <Table.Cell>{standing.tie}</Table.Cell>

                    <Table.Cell>{standing.lost}</Table.Cell>

                    <Table.Cell>{standing.scored}</Table.Cell>

                    <Table.Cell>{standing.conceded}</Table.Cell>

                    <Table.Cell>{standing.scored - standing.conceded}</Table.Cell>

                    <Table.Cell className="whitespace-nowrap font-medium text-gray-900 dark:text-white">
                      {standing.points}
                    </Table.Cell>
                  </Table.Row>
                ))}
              </Table.Body>
            </Table>
          ))}
        </div>
      </section>
    </Layout>
  )
}
