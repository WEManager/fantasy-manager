import _ from 'lodash'
import { Goalkeeping, Mental, Physical, Technical } from '@/interfaces/Player'

type PlayerShowStatListProps = {
  title: string
  stats: Technical | Mental | Goalkeeping | Physical
}

export default function PlayerShowStatList({ title, stats }: PlayerShowStatListProps) {
  return (
    <div>
      <h3 className="text-xl font-bold mb-3">{title}</h3>

      <ul>
        {Object.entries<number>(stats).map(([atribute, value]) => (
          <li key={atribute} className="flex justify-between">
            <span>{_.startCase(atribute)}</span>
            <span>{value}</span>
          </li>
        ))}
      </ul>
    </div>
  )
}
