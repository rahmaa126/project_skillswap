-- =====================================================
-- SkillSwap Database Schema (MySQL 8.0.16+ / InnoDB)
-- + Tambahan AI: smart matching & generate soal battle
-- =====================================================
-- Catatan penting MySQL:
--  * Wajib MySQL 8.0.16+ (CHECK constraint baru benar-benar dijalankan mulai versi ini).
--  * Semua waktu disimpan sebagai DATETIME dalam UTC (atur di aplikasi/koneksi: SET time_zone = '+00:00').
--  * FOREIGN KEY harus ditulis sebagai constraint tabel; "REFERENCES" inline DIABAIKAN oleh MySQL.
--  * MySQL melarang CHECK pada kolom yang punya FK dengan ON DELETE/UPDATE action, jadi aturan
--    "user_a <> user_b", "player1 <> player2", dll. divalidasi di aplikasi (lihat komentar [APP]).

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. REGISTRASI & LOGIN
-- =====================================================
CREATE TABLE users (
    id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name              VARCHAR(100)    NOT NULL,
    email             VARCHAR(150)    NOT NULL,
    password_hash     VARCHAR(255)    NOT NULL,
    role              ENUM('user','admin') NOT NULL DEFAULT 'user',
    is_active         TINYINT(1)      NOT NULL DEFAULT 1,
    email_verified_at DATETIME        NULL,
    last_login_at     DATETIME        NULL,
    created_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Untuk refresh token / sesi login (berguna jika pakai JWT + mobile/web)
CREATE TABLE auth_sessions (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id       BIGINT UNSIGNED NOT NULL,
    refresh_token VARCHAR(255)    NOT NULL,
    user_agent    VARCHAR(255)    NULL,
    ip_address    VARCHAR(45)     NULL,
    expires_at    DATETIME        NOT NULL,
    created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_auth_refresh_token (refresh_token),
    CONSTRAINT fk_auth_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE password_resets (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    token      VARCHAR(255)    NOT NULL,
    expires_at DATETIME        NOT NULL,
    used_at    DATETIME        NULL,
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_password_resets_token (token),
    CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. PROFIL PENGGUNA
-- =====================================================
CREATE TABLE profiles (
    user_id     BIGINT UNSIGNED NOT NULL,
    avatar_url  VARCHAR(255)    NULL,
    bio         TEXT            NULL,
    city        VARCHAR(100)    NULL,
    phone       VARCHAR(20)     NULL,
    -- denormalisasi agar query cepat, diperbarui saat ada review baru
    avg_rating  DECIMAL(3,2)    NOT NULL DEFAULT 0,
    total_swaps INT UNSIGNED    NOT NULL DEFAULT 0,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE skill_categories (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_skill_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE skills (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id INT UNSIGNED NULL,
    name        VARCHAR(100) NOT NULL,
    description TEXT         NULL,
    -- skor minimum agar user boleh ikut smart matching / battle di skill ini
    min_score   INT          NOT NULL DEFAULT 70,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_skills_name (name),
    CONSTRAINT fk_skills_category FOREIGN KEY (category_id) REFERENCES skill_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Skill yang ditawarkan (offered) dan yang ingin dipelajari (wanted)
CREATE TABLE user_skills (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    skill_id   INT UNSIGNED    NOT NULL,
    type       ENUM('offered','wanted') NOT NULL,
    level      ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_skill_type (user_id, skill_id, type),
    KEY idx_user_skills_skill (skill_id, type),
    CONSTRAINT fk_user_skills_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_user_skills_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. SMART MATCHING (BATTLE)
-- =====================================================
-- Skor user per skill (hasil tes/kuis/battle). Dipakai untuk cek min_score.
CREATE TABLE user_skill_scores (
    user_id    BIGINT UNSIGNED NOT NULL,
    skill_id   INT UNSIGNED    NOT NULL,
    score      INT             NOT NULL DEFAULT 0,
    updated_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, skill_id),
    KEY idx_scores_skill_score (skill_id, score DESC),
    CONSTRAINT fk_scores_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_scores_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- [AI] Log setiap pemanggilan AI (kontrol biaya, debugging, audit)
CREATE TABLE ai_logs (
    id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    feature           ENUM('matching','question_generation') NOT NULL,
    user_id           BIGINT UNSIGNED NULL,          -- siapa yang memicu (user/admin)
    skill_id          INT UNSIGNED    NULL,
    model             VARCHAR(100)    NOT NULL,
    prompt_tokens     INT UNSIGNED    NOT NULL DEFAULT 0,
    completion_tokens INT UNSIGNED    NOT NULL DEFAULT 0,
    status            ENUM('success','failed') NOT NULL DEFAULT 'success',
    error_message     TEXT            NULL,
    created_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_ai_logs_feature_time (feature, created_at),
    KEY idx_ai_logs_user (user_id),
    CONSTRAINT fk_ai_logs_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE SET NULL,
    CONSTRAINT fk_ai_logs_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bank soal untuk battle / penilaian skor (manual oleh admin ATAU dibuat AI)
CREATE TABLE battle_questions (
    id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    skill_id          INT UNSIGNED    NOT NULL,
    question          TEXT            NOT NULL,
    options           JSON            NOT NULL,       -- ["A...", "B...", "C...", "D..."]
    correct_option    SMALLINT        NOT NULL,       -- index jawaban benar (mulai dari 0)
    points            INT             NOT NULL DEFAULT 10,
    -- [AI] kolom tambahan untuk soal hasil generate
    difficulty        ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium',
    explanation       TEXT            NULL,           -- penjelasan jawaban (ditampilkan setelah battle)
    source            ENUM('manual','ai') NOT NULL DEFAULT 'manual',
    status            ENUM('draft','approved','rejected') NOT NULL DEFAULT 'approved',
                      -- soal AI masuk sebagai 'draft', baru dipakai battle setelah admin set 'approved'
    ai_model          VARCHAR(100)    NULL,
    generation_log_id BIGINT UNSIGNED NULL,
    reviewed_by       BIGINT UNSIGNED NULL,
    reviewed_at       DATETIME        NULL,
    -- hash untuk mencegah soal duplikat per skill
    question_hash     CHAR(64) GENERATED ALWAYS AS (SHA2(question, 256)) STORED,
    created_by        BIGINT UNSIGNED NULL,
    created_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_question_per_skill (skill_id, question_hash),
    KEY idx_questions_pick (skill_id, status, difficulty),
    CONSTRAINT chk_correct_option CHECK (correct_option >= 0),
    CONSTRAINT chk_options_array  CHECK (JSON_TYPE(options) = 'ARRAY' AND JSON_LENGTH(options) >= 2),
    CONSTRAINT fk_questions_skill      FOREIGN KEY (skill_id)          REFERENCES skills(id)  ON DELETE CASCADE,
    CONSTRAINT fk_questions_created_by FOREIGN KEY (created_by)        REFERENCES users(id)   ON DELETE SET NULL,
    CONSTRAINT fk_questions_reviewed   FOREIGN KEY (reviewed_by)       REFERENCES users(id)   ON DELETE SET NULL,
    CONSTRAINT fk_questions_ai_log     FOREIGN KEY (generation_log_id) REFERENCES ai_logs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- [AI] Embedding skill untuk pencocokan semantik
-- (MySQL tidak punya tipe vector bawaan yang bisa dipakai umum, jadi embedding disimpan sebagai JSON
--  dan cosine similarity dihitung di aplikasi. Cukup untuk ratusan-ribuan skill.)
CREATE TABLE skill_embeddings (
    skill_id     INT UNSIGNED NOT NULL,
    model        VARCHAR(100) NOT NULL,
    embedding    JSON         NOT NULL,                 -- [0.0123, -0.0456, ...]
    content_hash CHAR(64)     NOT NULL,                 -- hash teks (nama+deskripsi) yang di-embed; beda = perlu re-embed
    updated_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (skill_id, model),
    CONSTRAINT fk_skill_embeddings_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hasil smart matching: pasangan user yang cocok
-- [APP] Pastikan user_a_id <> user_b_id, dan normalisasi urutan (mis. user_a_id < user_b_id)
--       supaya UNIQUE di bawah efektif mencegah duplikat.
CREATE TABLE matches (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_a_id       BIGINT UNSIGNED NOT NULL,
    user_b_id       BIGINT UNSIGNED NOT NULL,
    skill_a_id      INT UNSIGNED    NOT NULL,           -- skill yang ditawarkan A
    skill_b_id      INT UNSIGNED    NOT NULL,           -- skill yang ditawarkan B
    match_score     DECIMAL(5,2)    NOT NULL DEFAULT 0,
    status          ENUM('suggested','accepted','rejected','expired') NOT NULL DEFAULT 'suggested',
    -- [AI] kolom tambahan
    match_source    ENUM('rule_based','ai_semantic') NOT NULL DEFAULT 'rule_based',
    score_breakdown JSON            NULL,               -- mis. {"semantic":0.82,"level":0.9,"rating":0.7}
    ai_reason       TEXT            NULL,               -- alasan singkat kenapa cocok (ditampilkan ke user)
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_match_pair (user_a_id, user_b_id, skill_a_id, skill_b_id),
    KEY idx_matches_users (user_a_id, user_b_id),
    KEY idx_matches_b (user_b_id),
    CONSTRAINT fk_matches_user_a  FOREIGN KEY (user_a_id)  REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_matches_user_b  FOREIGN KEY (user_b_id)  REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_matches_skill_a FOREIGN KEY (skill_a_id) REFERENCES skills(id),
    CONSTRAINT fk_matches_skill_b FOREIGN KEY (skill_b_id) REFERENCES skills(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Battle antar dua user (hanya yang skornya >= min_score)
-- [APP] Pastikan player1_id <> player2_id.
CREATE TABLE battles (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    skill_id    INT UNSIGNED    NOT NULL,
    match_id    BIGINT UNSIGNED NULL,
    player1_id  BIGINT UNSIGNED NOT NULL,
    player2_id  BIGINT UNSIGNED NOT NULL,
    winner_id   BIGINT UNSIGNED NULL,
    status      ENUM('pending','ongoing','finished','cancelled') NOT NULL DEFAULT 'pending',
    started_at  DATETIME        NULL,
    finished_at DATETIME        NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_battles_players (player1_id, player2_id),
    KEY idx_battles_player2 (player2_id),
    CONSTRAINT fk_battles_skill   FOREIGN KEY (skill_id)   REFERENCES skills(id),
    CONSTRAINT fk_battles_match   FOREIGN KEY (match_id)   REFERENCES matches(id) ON DELETE SET NULL,
    CONSTRAINT fk_battles_p1      FOREIGN KEY (player1_id) REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_battles_p2      FOREIGN KEY (player2_id) REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_battles_winner  FOREIGN KEY (winner_id)  REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE battle_answers (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    battle_id     BIGINT UNSIGNED NOT NULL,
    question_id   BIGINT UNSIGNED NOT NULL,
    user_id       BIGINT UNSIGNED NOT NULL,
    chosen_option SMALLINT        NULL,
    is_correct    TINYINT(1)      NULL,
    points_earned INT             NOT NULL DEFAULT 0,
    answered_at   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_battle_question_user (battle_id, question_id, user_id),
    KEY idx_answers_question (question_id),
    KEY idx_answers_user (user_id),
    CONSTRAINT fk_answers_battle   FOREIGN KEY (battle_id)   REFERENCES battles(id)          ON DELETE CASCADE,
    CONSTRAINT fk_answers_question FOREIGN KEY (question_id) REFERENCES battle_questions(id) ON DELETE CASCADE,
    CONSTRAINT fk_answers_user     FOREIGN KEY (user_id)     REFERENCES users(id)            ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. PERTUKARAN SKILL (CHAT & VIDEO CALL DI DASHBOARD)
-- =====================================================
-- [APP] Pastikan requester_id <> partner_id.
CREATE TABLE swap_sessions (
    id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    match_id           BIGINT UNSIGNED NULL,
    requester_id       BIGINT UNSIGNED NOT NULL,
    partner_id         BIGINT UNSIGNED NOT NULL,
    requester_skill_id INT UNSIGNED    NOT NULL,        -- skill yang diajarkan requester
    partner_skill_id   INT UNSIGNED    NOT NULL,        -- skill yang diajarkan partner
    status             ENUM('pending','accepted','rejected','ongoing','completed','cancelled') NOT NULL DEFAULT 'pending',
    scheduled_at       DATETIME        NULL,
    started_at         DATETIME        NULL,
    completed_at       DATETIME        NULL,
    created_at         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_swap_requester (requester_id, status),
    KEY idx_swap_partner (partner_id, status),
    CONSTRAINT fk_swap_match           FOREIGN KEY (match_id)           REFERENCES matches(id) ON DELETE SET NULL,
    CONSTRAINT fk_swap_requester       FOREIGN KEY (requester_id)       REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_swap_partner         FOREIGN KEY (partner_id)         REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_swap_requester_skill FOREIGN KEY (requester_skill_id) REFERENCES skills(id),
    CONSTRAINT fk_swap_partner_skill   FOREIGN KEY (partner_skill_id)   REFERENCES skills(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE messages (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    session_id BIGINT UNSIGNED NOT NULL,
    sender_id  BIGINT UNSIGNED NOT NULL,
    type       ENUM('text','image','file','system') NOT NULL DEFAULT 'text',
    content    TEXT            NOT NULL,                -- teks, atau URL jika image/file
    read_at    DATETIME        NULL,
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_messages_session_time (session_id, created_at),
    KEY idx_messages_sender (sender_id),
    CONSTRAINT fk_messages_session FOREIGN KEY (session_id) REFERENCES swap_sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_sender  FOREIGN KEY (sender_id)  REFERENCES users(id)         ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE video_calls (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    session_id       BIGINT UNSIGNED NOT NULL,
    room_id          VARCHAR(100)    NOT NULL,          -- ID room WebRTC / provider (Agora, Twilio, dll)
    started_at       DATETIME        NULL,
    ended_at         DATETIME        NULL,
    duration_seconds INT UNSIGNED    NULL,
    created_at       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_video_calls_room (room_id),
    KEY idx_video_calls_session (session_id),
    CONSTRAINT fk_video_calls_session FOREIGN KEY (session_id) REFERENCES swap_sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. RATING & REVIEW
-- =====================================================
-- [APP] Pastikan reviewer_id <> reviewee_id.
CREATE TABLE reviews (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    session_id  BIGINT UNSIGNED NOT NULL,
    reviewer_id BIGINT UNSIGNED NOT NULL,
    reviewee_id BIGINT UNSIGNED NOT NULL,
    rating      TINYINT UNSIGNED NOT NULL,
    comment     TEXT            NULL,
    is_hidden   TINYINT(1)      NOT NULL DEFAULT 0,     -- dimoderasi admin
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_review_per_session (session_id, reviewer_id),   -- 1 review per user per sesi
    KEY idx_reviews_reviewee (reviewee_id),
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
    CONSTRAINT fk_reviews_session  FOREIGN KEY (session_id)  REFERENCES swap_sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id)         ON DELETE CASCADE,
    CONSTRAINT fk_reviews_reviewee FOREIGN KEY (reviewee_id) REFERENCES users(id)         ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PENDUKUNG (opsional, berguna untuk admin & dashboard)
-- =====================================================
CREATE TABLE notifications (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    title      VARCHAR(150)    NOT NULL,
    body       TEXT            NULL,
    is_read    TINYINT(1)      NOT NULL DEFAULT 0,
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_notifications_user (user_id, is_read),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reports (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reporter_id BIGINT UNSIGNED NOT NULL,
    reported_id BIGINT UNSIGNED NOT NULL,
    session_id  BIGINT UNSIGNED NULL,
    reason      TEXT            NOT NULL,
    status      ENUM('open','resolved','dismissed') NOT NULL DEFAULT 'open',
    handled_by  BIGINT UNSIGNED NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_reports_status (status),
    KEY idx_reports_reporter (reporter_id),
    KEY idx_reports_reported (reported_id),
    CONSTRAINT fk_reports_reporter FOREIGN KEY (reporter_id) REFERENCES users(id)         ON DELETE CASCADE,
    CONSTRAINT fk_reports_reported FOREIGN KEY (reported_id) REFERENCES users(id)         ON DELETE CASCADE,
    CONSTRAINT fk_reports_session  FOREIGN KEY (session_id)  REFERENCES swap_sessions(id) ON DELETE SET NULL,
    CONSTRAINT fk_reports_handler  FOREIGN KEY (handled_by)  REFERENCES users(id)         ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
