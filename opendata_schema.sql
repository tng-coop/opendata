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
    json json NOT NULL
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
-- PostgreSQL database dump complete
--

