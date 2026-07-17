 1. Infrastructure (WebSockets)

  - composer require laravel/reverb + php artisan install:broadcasting → installs Reverb, creates config/reverb.php,
  config/broadcasting.php, routes/channels.php, and scaffolds Echo.
  - npm install laravel-echo pusher-js; wire Echo into a new resources/js/quiz.js entry (keeps it out of both the
  Gentelella app.js and the Bootstrap login.js — same split-entry pattern we just established). Add it to
  vite.config.js.
  - .env: BROADCAST_CONNECTION=reverb + REVERB_* keys.
  - Broadcast strategy: use ShouldBroadcastNow (synchronous) for the counter/status/leaderboard events. This means no
  queue:work worker needed — simpler ops, instant delivery. (Note in plan: switch to queued if scale grows.)
  - Dev run: three processes — php artisan serve, npm run dev, php artisan reverb:start.

  2. Data model (migrations + Eloquent models)

  - quiz_sessions — title, join_code (6-digit string), status enum(lobby/live/ended), created_by, started_at, ended_at,
  default_time_limit. Unique index on join_code among active sessions (enforced in validation, since ended codes can be
  reused).
  - questions — quiz_session_id, text, position, time_limit, points.
  - question_options — question_id, text, is_correct (idiomatic over a JSON column).
  - participants — quiz_session_id, phone, name?, score, joined_at, finished_at. Unique(quiz_session_id, phone) = one
  entry per phone per quiz; this table is the counter source.
  - answers — participant_id, question_id, question_option_id? (null = timed out), is_correct, response_ms,
  points_awarded. Unique(participant_id, question_id) = one answer per question; also drives self-paced progress
  tracking.
  - Models with relationships + a QuizSession::generateUniqueCode() helper.

  3. Broadcast events (public channel quiz.{id})

  Public channel — participants aren't Laravel-authed, and only non-sensitive aggregate data is broadcast.
  - ParticipantJoined → { total } — fires the increment counter.
  - QuizStatusChanged → { status } — flips lobby screens into the quiz when admin hits Start, and to results on End.
  - LeaderboardUpdated → top N — fires as answers land.

  4. Participant flow (Bootstrap, public routes)

  A dedicated public layouts/quiz layout (loads the Bootstrap quiz.js entry). Livewire components:
  - JoinQuiz (/join) — code + phone form. Validates: active lobby session with that code; phone not already in it.
  Creates Participant, stores participant_id in session (survives refresh), fires ParticipantJoined.
  - QuizLobby — waiting screen with the animated live counter (Alpine tween + Echo listener); auto-advances when
  QuizStatusChanged → live.
  - QuizPlay — self-paced engine: serves the participant's next unanswered question, per-question Alpine countdown
  (auto-submits blank on timeout), scores on submit (correctness + optional speed bonus), advances. Server re-validates
  every answer.
  - QuizResults — final score + live leaderboard.

  5. Admin flow (Gentelella, behind existing admin auth)

  - QuizIndex (/admin/quizzes) — list/create sessions.
  - QuizEditor — manage questions/options; auto-generate or set the 6-digit code.
  - QuizControl (host screen) — big join code to project, live join counter, Start/End buttons (fire QuizStatusChanged),
  and a live leaderboard.

  6. The increment counter (the piece you're most excited about)

  Alpine component holding count; an Echo listener on quiz.{id} updates it on ParticipantJoined; a requestAnimationFrame
  tween animates old→new value. DB-backed total = monotonic, refresh-proof.

  7. Testing

  Feature tests: join validation (bad code, duplicate phone, non-lobby), scoring correctness, one-answer-per-question,
  code uniqueness among active sessions. Per AGENTS.md, run php artisan test after each meaningful step.

  Build order

  1. Reverb + Echo + quiz.js entry — prove a broadcast reaches the browser.
  2. Migrations + models.
  3. Join page (code + phone) → creates participant.
  4. Live increment counter end-to-end (lobby + admin control).
  5. Admin quiz/question editor + Start/End status flips.
  6. Self-paced play engine (timer, answer, scoring).
  7. Leaderboard + results + tests.

  ---
  Trade-offs baked in: public channels (no per-user auth needed, only aggregate data exposed) · ShouldBroadcastNow (no
  queue worker) · separate quiz.js Vite entry (isolates Bootstrap from Gentelella) · session-stored participant identity
  (no OTP, refresh-safe).