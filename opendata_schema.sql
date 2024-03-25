--
-- PostgreSQL database dump
--

-- Dumped from database version 15.6 (Ubuntu 15.6-0ubuntu0.23.10.1)
-- Dumped by pg_dump version 15.6 (Ubuntu 15.6-0ubuntu0.23.10.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: opendata; Type: TABLE; Schema: public; Owner: yasu
--

CREATE TABLE public.opendata (
    id uuid NOT NULL,
    json json DEFAULT '[]'::json NOT NULL,
    password text,
    email text,
    last_update timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.opendata OWNER TO yasu;

--
-- Name: opendata id_unique; Type: CONSTRAINT; Schema: public; Owner: yasu
--

ALTER TABLE ONLY public.opendata
    ADD CONSTRAINT id_unique UNIQUE (id);


--
-- Name: opendata opendata_pkey; Type: CONSTRAINT; Schema: public; Owner: yasu
--

ALTER TABLE ONLY public.opendata
    ADD CONSTRAINT opendata_pkey PRIMARY KEY (id);


--
-- Name: idx_encrypted_email; Type: INDEX; Schema: public; Owner: yasu
--

CREATE INDEX idx_encrypted_email ON public.opendata USING btree (email);


--
-- Name: opendata set_last_update; Type: TRIGGER; Schema: public; Owner: yasu
--

CREATE TRIGGER set_last_update BEFORE INSERT OR UPDATE ON public.opendata FOR EACH ROW EXECUTE FUNCTION public.update_last_modified();


--
-- PostgreSQL database dump complete
--

