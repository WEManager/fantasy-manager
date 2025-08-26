interface FlagIconProps {
  nationality: string
  className?: string
}

export function FlagIcon({ nationality, className = '' }: FlagIconProps) {
  return (
    <span
      className={`flag-icon flag-icon-${nationality.toLowerCase()} ${className}`}
      style={{
        width: '1.2em',
        height: '1.2em',
        display: 'inline-block',
        backgroundSize: 'contain',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
      }}
    />
  )
}
