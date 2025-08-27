import type { FormEvent } from 'react'

import { Head, useForm } from '@inertiajs/react'

import { Alert, AlertDescription } from '~/modules/core/components/ui/alert'
import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'
import { Label } from '~/modules/core/components/ui/label'
import { RadioGroup, RadioGroupItem } from '~/modules/core/components/ui/radio-group'

interface Question {
  id: number
  name: string
  question: string
  answers: string[]
}

interface Quiz {
  id: number
  name: string
  questions: Question[]
}

interface LicenseTestPageProps {
  quiz: Quiz
  errors?: Record<string, string[]>
}

export default function LicenseTestPage({ quiz, errors }: LicenseTestPageProps) {
  // Initialize form data with empty values for each question
  const initialData = quiz.questions.reduce(
    (acc, question) => {
      if (question.id) {
        acc[question.id.toString()] = ''
      }
      return acc
    },
    {} as Record<string, string>,
  )

  const { data, setData, post, processing } = useForm<Record<string, string>>(initialData)

  const onHandleChange = (questionId: number, value: string) => {
    setData(questionId.toString(), value)
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('license_test_validation', { licenseQuiz: quiz.id }))
  }

  const errorCount = errors ? Object.keys(errors).length : 0

  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={quiz.name} />

      <div className="container mx-auto max-w-2xl">
        <Card>
          <CardHeader>
            <CardTitle className="text-2xl">{quiz.name}</CardTitle>
            <CardDescription>Teste de licença para gerente nível 1</CardDescription>
          </CardHeader>

          <CardContent>
            {errors && Object.keys(errors).length > 0 && (
              <Alert className="mb-6 border-destructive">
                <AlertDescription>
                  Por favor, verifique novamente. {errorCount} de suas respostas estava incorreta.
                </AlertDescription>
              </Alert>
            )}

            <form onSubmit={submit} className="space-y-6">
              {quiz.questions.map((question, index) => {
                // Skip questions without name or answers
                if (!question.question || !question.answers.length) {
                  return null
                }

                const questionId = question.id.toString()
                const isQuestionWrong = errors?.[questionId]
                const isQuestionCorrect = errors && !errors[questionId]

                return (
                  <Card key={question.id} className="border-border">
                    <CardContent className="pt-6">
                      <div className="space-y-4">
                        <div>
                          <h3 className="font-semibold text-lg">
                            {index + 1}. {question.question}
                          </h3>
                        </div>

                        {isQuestionWrong && (
                          <Alert className="border-destructive">
                            <AlertDescription>Resposta incorreta.</AlertDescription>
                          </Alert>
                        )}

                        {isQuestionCorrect && (
                          <Alert className="border-green-200 bg-green-50 dark:bg-green-900/20">
                            <AlertDescription className="text-green-700 dark:text-green-400">
                              Resposta correta.
                            </AlertDescription>
                          </Alert>
                        )}

                        <RadioGroup
                          value={data[questionId] || ''}
                          onValueChange={(value: string) => onHandleChange(question.id, value)}
                          className="space-y-3"
                        >
                          {question.answers.map((answer) => (
                            <div key={answer} className="flex items-center space-x-2">
                              <RadioGroupItem value={answer} id={`${questionId}-${answer}`} />
                              <Label
                                htmlFor={`${questionId}-${answer}`}
                                className="text-sm font-normal cursor-pointer"
                              >
                                {answer}
                              </Label>
                            </div>
                          ))}
                        </RadioGroup>
                      </div>
                    </CardContent>
                  </Card>
                )
              })}

              <div className="flex justify-end pt-4">
                <Button type="submit" disabled={processing}>
                  {processing ? 'Enviando...' : 'Enviar respostas'}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
