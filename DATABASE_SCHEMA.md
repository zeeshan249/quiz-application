# Database Schema — batchlinks_db

> MySQL · Laravel 13 · Generated 2026-08-06

---

## Table of Contents

- [Application Tables](#application-tables)
  - [users](#users)
  - [question_sets](#question_sets)
  - [questions](#questions)
  - [question_options](#question_options)
  - [quiz_sessions](#quiz_sessions)
  - [participants](#participants)
  - [answers](#answers)
- [Laravel System Tables](#laravel-system-tables)
  - [sessions](#sessions)
  - [cache / cache_locks](#cache--cache_locks)
  - [jobs / job_batches / failed_jobs](#jobs--job_batches--failed_jobs)
  - [migrations](#migrations)
  - [password_reset_tokens](#password_reset_tokens)
- [Relationship Overview](#relationship-overview)

---

## Application Tables

---

### users

Stores registered users (quiz hosts/creators).

| Column              | Type               | Nullable | Default | Notes                          |
|---------------------|--------------------|----------|---------|--------------------------------|
| id                  | bigint unsigned    | NO       | —       | Primary key, auto-increment    |
| name                | varchar(255)       | NO       | —       |                                |
| email               | varchar(255)       | NO       | —       | Unique                         |
| email_verified_at   | timestamp          | YES      | NULL    |                                |
| password            | varchar(255)       | NO       | —       |                                |
| user_type           | tinyint unsigned   | NO       | —       | Role flag                      |
| remember_token      | varchar(100)       | YES      | NULL    |                                |
| created_at          | timestamp          | YES      | NULL    |                                |
| updated_at          | timestamp          | YES      | NULL    |                                |

**Relationships:**
<!-- users is referenced BY question_sets.created_by (cascade delete) -->
<!-- users is referenced BY quiz_sessions.created_by (set null on delete) -->
<!-- users is referenced BY sessions.user_id (Laravel session driver) -->

---

### question_sets

A named collection of questions created by a user.

| Column      | Type            | Nullable | Default | Notes                                |
|-------------|-----------------|----------|---------|--------------------------------------|
| id          | bigint unsigned | NO       | —       | Primary key, auto-increment          |
| title       | varchar(255)    | NO       | —       |                                      |
| description | text            | YES      | NULL    |                                      |
| created_by  | bigint unsigned | NO       | —       | FK → users.id (cascade delete)       |
| is_active   | tinyint(1)      | NO       | 1       | Soft-toggle visibility               |
| created_at  | timestamp       | YES      | NULL    |                                      |
| updated_at  | timestamp       | YES      | NULL    |                                      |

**Relationships:**
<!-- question_sets.created_by → users.id  (belongs to a user; deletes set when user deleted) -->
<!-- question_sets is referenced BY questions.question_set_id (cascade delete) -->
<!-- question_sets is referenced BY quiz_sessions.question_set_id (set null on delete) -->

---

### questions

Individual questions belonging to a question set.

| Column          | Type            | Nullable | Default | Notes                                       |
|-----------------|-----------------|----------|---------|---------------------------------------------|
| id              | bigint unsigned | NO       | —       | Primary key, auto-increment                 |
| question_set_id | bigint unsigned | NO       | —       | FK → question_sets.id (cascade delete)      |
| text            | text            | NO       | —       | Question body                               |
| position        | int unsigned    | NO       | 0       | Display order within the set                |
| points          | int unsigned    | NO       | 1       | Points awarded for a correct answer         |
| time_limit      | smallint unsigned | YES    | NULL    | Per-question override in seconds (nullable) |
| created_at      | timestamp       | YES      | NULL    |                                             |
| updated_at      | timestamp       | YES      | NULL    |                                             |

**Indexes:** `(position)` · `questions_question_set_id_foreign`

**Relationships:**
<!-- questions.question_set_id → question_sets.id (belongs to a set; deleted with the set) -->
<!-- questions is referenced BY question_options.question_id (cascade delete) -->
<!-- questions is referenced BY answers.question_id (cascade delete) -->
<!-- questions is referenced BY quiz_sessions.current_question_id (set null on delete) -->

---

### question_options

The answer choices for a question. One or more may be marked correct.

| Column      | Type            | Nullable | Default | Notes                                   |
|-------------|-----------------|-----------|---------|-----------------------------------------|
| id          | bigint unsigned | NO        | —       | Primary key, auto-increment             |
| question_id | bigint unsigned | NO        | —       | FK → questions.id (cascade delete)      |
| text        | varchar(255)    | NO        | —       | Option label shown to participants      |
| is_correct  | tinyint(1)      | NO        | 0       | 1 = correct answer                      |
| position    | int unsigned    | NO        | 0       | Display order within the question       |
| created_at  | timestamp       | YES       | NULL    |                                         |
| updated_at  | timestamp       | YES       | NULL    |                                         |

**Indexes:** `(question_id, position)`

**Relationships:**
<!-- question_options.question_id → questions.id (belongs to a question; deleted with the question) -->
<!-- question_options is referenced BY answers.question_option_id (set null on delete) -->

---

### quiz_sessions

A live game session based on a question set.

| Column              | Type                                    | Nullable | Default | Notes                                              |
|---------------------|-----------------------------------------|----------|---------|----------------------------------------------------|
| id                  | bigint unsigned                         | NO       | —       | Primary key, auto-increment                        |
| question_set_id     | bigint unsigned                         | YES      | NULL    | FK → question_sets.id (set null on delete)         |
| title               | varchar(255)                            | NO       | —       | Display name of the session                        |
| join_code           | int unsigned                            | NO       | —       | Numeric code participants use to join              |
| status              | enum('draft','lobby','live','ended')    | NO       | draft   | Lifecycle state                                    |
| created_by          | bigint unsigned                         | YES      | NULL    | FK → users.id (set null on delete)                 |
| answer_seconds      | smallint unsigned                       | NO       | 20      | Default seconds to answer per question             |
| reveal_seconds      | smallint unsigned                       | NO       | 6       | Seconds to show answer reveal screen               |
| current_question_id | bigint unsigned                         | YES      | NULL    | FK → questions.id (set null on delete)             |
| phase               | enum('question','reveal')               | YES      | NULL    | Current phase within a live question cycle         |
| phase_ends_at       | timestamp                               | YES      | NULL    | When the current phase expires                     |
| started_at          | timestamp                               | YES      | NULL    |                                                    |
| ended_at            | timestamp                               | YES      | NULL    |                                                    |
| created_at          | timestamp                               | YES      | NULL    |                                                    |
| updated_at          | timestamp                               | YES      | NULL    |                                                    |

**Indexes:** `(join_code, status)` · `quiz_sessions_created_by_foreign` · `quiz_sessions_current_question_id_foreign`

**Relationships:**
<!-- quiz_sessions.question_set_id → question_sets.id (based on a set; set null if set deleted) -->
<!-- quiz_sessions.created_by → users.id (belongs to a host user; set null if user deleted) -->
<!-- quiz_sessions.current_question_id → questions.id (tracks active question; set null if question deleted) -->
<!-- quiz_sessions is referenced BY participants.quiz_session_id (cascade delete) -->

---

### participants

Players who have joined a quiz session.

| Column          | Type            | Nullable | Default | Notes                                       |
|-----------------|-----------------|----------|---------|---------------------------------------------|
| id              | bigint unsigned | NO       | —       | Primary key, auto-increment                 |
| quiz_session_id | bigint unsigned | NO       | —       | FK → quiz_sessions.id (cascade delete)      |
| name            | varchar(255)    | NO       | —       | Display name chosen by the participant      |
| token           | varchar(64)     | NO       | —       | Auth token for WebSocket identity           |
| score           | int unsigned    | NO       | 0       | Running total of points_awarded             |
| joined_at       | timestamp       | YES      | NULL    |                                             |
| finished_at     | timestamp       | YES      | NULL    | Set when participant completes the session  |
| created_at      | timestamp       | YES      | NULL    |                                             |
| updated_at      | timestamp       | YES      | NULL    |                                             |

**Indexes:** `(quiz_session_id, token)` UNIQUE

**Relationships:**
<!-- participants.quiz_session_id → quiz_sessions.id (belongs to a session; deleted with the session) -->
<!-- participants is referenced BY answers.participant_id (cascade delete) -->

---

### answers

One row per participant per question — records what option was chosen and whether it was correct.

| Column             | Type            | Nullable | Default | Notes                                             |
|--------------------|-----------------|----------|---------|---------------------------------------------------|
| id                 | bigint unsigned | NO       | —       | Primary key, auto-increment                       |
| participant_id     | bigint unsigned | NO       | —       | FK → participants.id (cascade delete)             |
| question_id        | bigint unsigned | NO       | —       | FK → questions.id (cascade delete)                |
| question_option_id | bigint unsigned | YES      | NULL    | FK → question_options.id (set null on delete)     |
| is_correct         | tinyint(1)      | NO       | 0       | Denormalized flag for fast leaderboard queries    |
| response_ms        | int unsigned    | YES      | NULL    | Time taken to answer in milliseconds              |
| points_awarded     | int unsigned    | NO       | 0       | Points granted for this answer                    |
| created_at         | timestamp       | YES      | NULL    |                                                   |
| updated_at         | timestamp       | YES      | NULL    |                                                   |

**Indexes:** `(participant_id, question_id)` UNIQUE · `(question_id, is_correct)`

**Relationships:**
<!-- answers.participant_id → participants.id (belongs to a participant; deleted with participant) -->
<!-- answers.question_id → questions.id (belongs to a question; deleted with question) -->
<!-- answers.question_option_id → question_options.id (the chosen option; set null if option deleted) -->

---

## Laravel System Tables

---

### sessions

Laravel database session driver — maps session ID to an authenticated user.

| Column        | Type            | Nullable |
|---------------|-----------------|----------|
| id            | varchar(255)    | NO       |
| user_id       | bigint unsigned | YES      |
| ip_address    | varchar(45)     | YES      |
| user_agent    | text            | YES      |
| payload       | longtext        | NO       |
| last_activity | int             | NO       |

<!-- sessions.user_id → users.id (no FK constraint; managed by Laravel session driver) -->

---

### cache / cache_locks

Laravel cache driver tables.

**cache:** `key` (PK), `value`, `expiration`  
**cache_locks:** `key` (PK), `owner`, `expiration`

---

### jobs / job_batches / failed_jobs

Laravel queue driver tables.

**jobs:** `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`  
**job_batches:** `id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`  
**failed_jobs:** `id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`

---

### migrations

Laravel migration tracker. `id`, `migration`, `batch`.

---

### password_reset_tokens

Laravel password-reset flow. `email` (PK), `token`, `created_at`.

---

## Relationship Overview

```
users
 ├─── question_sets (created_by)          1 user → many question_sets
 └─── quiz_sessions (created_by)          1 user → many quiz_sessions

question_sets
 ├─── questions (question_set_id)         1 set  → many questions
 └─── quiz_sessions (question_set_id)     1 set  → many quiz_sessions

questions
 ├─── question_options (question_id)      1 question → many options
 ├─── answers (question_id)              1 question → many answers
 └─── quiz_sessions (current_question_id) tracks which question is live

question_options
 └─── answers (question_option_id)        1 option   → many answers

quiz_sessions
 └─── participants (quiz_session_id)      1 session  → many participants

participants
 └─── answers (participant_id)            1 participant → many answers
```

### Foreign Key Cascade Summary

| FK Column                          | References                  | On Delete  |
|------------------------------------|-----------------------------|------------|
| question_sets.created_by           | users.id                    | CASCADE    |
| questions.question_set_id          | question_sets.id            | CASCADE    |
| question_options.question_id       | questions.id                | CASCADE    |
| quiz_sessions.created_by           | users.id                    | SET NULL   |
| quiz_sessions.question_set_id      | question_sets.id            | SET NULL   |
| quiz_sessions.current_question_id  | questions.id                | SET NULL   |
| participants.quiz_session_id       | quiz_sessions.id            | CASCADE    |
| answers.participant_id             | participants.id             | CASCADE    |
| answers.question_id                | questions.id                | CASCADE    |
| answers.question_option_id         | question_options.id         | SET NULL   |
